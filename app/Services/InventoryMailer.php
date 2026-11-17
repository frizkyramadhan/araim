<?php

namespace App\Services;

use App\Mail\InventoryCreatedMail;
use App\Mail\InventoryTransferredMail;
use App\Models\Employee;
use App\Models\Inventory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use stdClass;

class InventoryMailer
{
    const CC_SETTINGS_PATH = 'settings/inventory_mail_cc.json';

    /**
     * Notify assignee that an inventory record was created.
     *
     * @return array{sent: bool, skipped: bool, message: string|null}
     */
    public function notifyCreated(Inventory $inventory): array
    {
        $payload = $this->buildPayload($inventory);
        $to = $this->resolveEmployeeEmail($inventory->employee_id);

        if ($to === null) {
            return [
                'sent' => false,
                'skipped' => true,
                'message' => 'Inventory saved but email not sent (employee has no valid email)',
            ];
        }

        return $this->sendMailable(new InventoryCreatedMail($payload), $to, $this->defaultCc());
    }

    /**
     * Notify new assignee that inventory was transferred.
     *
     * @return array{sent: bool, skipped: bool, message: string|null}
     */
    public function notifyTransferred(Inventory $oldInventory, Inventory $newInventory): array
    {
        $payload = $this->buildPayload($newInventory);
        $fromPayload = $this->buildPayload($oldInventory);
        $to = $this->resolveEmployeeEmail($newInventory->employee_id);

        if ($to === null) {
            return [
                'sent' => false,
                'skipped' => true,
                'message' => 'Inventory transferred but email not sent (employee has no valid email)',
            ];
        }

        return $this->sendMailable(
            new InventoryTransferredMail($payload, $fromPayload),
            $to,
            $this->defaultCc()
        );
    }

    /**
     * Build view payload for preview or send-test.
     *
     * @return array{inventory: object, fromInventory: object|null}
     */
    public function previewData(string $type, int $inventoryId, ?int $fromInventoryId = null): array
    {
        $inventory = Inventory::findOrFail($inventoryId);
        $payload = $this->buildPayload($inventory);

        $fromPayload = null;
        if ($type === 'transferred') {
            if ($fromInventoryId) {
                $fromInventory = Inventory::find($fromInventoryId);
                $fromPayload = $fromInventory ? $this->buildPayload($fromInventory) : null;
            }
        }

        return [
            'inventory' => $payload,
            'fromInventory' => $fromPayload,
        ];
    }

    /**
     * Send a test email to custom recipients using real inventory data.
     *
     * @param  string|array  $to
     * @param  string|array|null  $cc
     * @return array{sent: bool, skipped: bool, message: string|null}
     */
    public function sendTest(string $type, int $inventoryId, $to, $cc = null, ?int $fromInventoryId = null): array
    {
        $data = $this->previewData($type, $inventoryId, $fromInventoryId);
        $toEmails = $this->normalizeEmails($to);
        $ccEmails = $this->normalizeEmails($cc ?? []);

        if (empty($toEmails)) {
            return [
                'sent' => false,
                'skipped' => true,
                'message' => 'No valid TO email address provided',
            ];
        }

        if ($type === 'transferred') {
            $mailable = new InventoryTransferredMail($data['inventory'], $data['fromInventory']);
        } else {
            $mailable = new InventoryCreatedMail($data['inventory']);
        }

        return $this->sendMailable($mailable, $toEmails, $ccEmails);
    }

    /**
     * Flatten inventory + relations into a plain object for Blade views.
     */
    public function buildPayload(Inventory $inventory): object
    {
        $inventory->loadMissing(['asset', 'brand', 'project', 'department', 'location', 'employee']);

        $payload = new stdClass();
        $payload->id = $inventory->id;
        $payload->inventory_no = $inventory->inventory_no;
        $payload->input_date = $inventory->input_date;
        $payload->model_asset = $inventory->model_asset;
        $payload->serial_no = $inventory->serial_no;
        $payload->part_no = $inventory->part_no;
        $payload->po_no = $inventory->po_no;
        $payload->quantity = $inventory->quantity;
        $payload->remarks = $inventory->remarks;
        $payload->inventory_status = $inventory->inventory_status;
        $payload->transfer_status = $inventory->transfer_status;
        $payload->reference_no = $inventory->reference_no;
        $payload->reference_date = $inventory->reference_date;
        $payload->asset_name = optional($inventory->asset)->asset_name;
        $payload->brand_name = optional($inventory->brand)->brand_name;
        $payload->project_code = optional($inventory->project)->project_code;
        $payload->project_name = optional($inventory->project)->project_name;
        $payload->dept_name = optional($inventory->department)->dept_name;
        $payload->location_name = optional($inventory->location)->location_name;
        $payload->employee_name = optional($inventory->employee)->fullname;
        $payload->employee_nik = optional($inventory->employee)->nik;
        $payload->employee_email = optional($inventory->employee)->email;

        return $payload;
    }

    /**
     * Production CC list managed from email-debug page.
     * Falls back to config/INVENTORY_MAIL_CC only when no saved settings exist yet.
     *
     * @return string[]
     */
    public function defaultCc(): array
    {
        if (Storage::disk('local')->exists(self::CC_SETTINGS_PATH)) {
            $raw = Storage::disk('local')->get(self::CC_SETTINGS_PATH);
            $decoded = json_decode($raw, true);
            $emails = is_array($decoded) ? ($decoded['emails'] ?? []) : [];

            return $this->normalizeEmails($emails);
        }

        $cc = config('mail.inventory_cc', []);

        return $this->normalizeEmails(is_array($cc) ? $cc : []);
    }

    /**
     * Persist CC list from admin email-debug settings.
     *
     * @param  mixed  $emails
     * @return string[]
     */
    public function saveCc($emails): array
    {
        $normalized = $this->normalizeEmails($emails);

        Storage::disk('local')->put(
            self::CC_SETTINGS_PATH,
            json_encode(['emails' => $normalized], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return $normalized;
    }

    /**
     * Whether CC is currently driven by saved admin settings (not env fallback).
     */
    public function hasSavedCcSettings(): bool
    {
        return Storage::disk('local')->exists(self::CC_SETTINGS_PATH);
    }

    /**
     * @param  mixed  $emails
     * @return string[]
     */
    public function normalizeEmails($emails): array
    {
        if (is_string($emails)) {
            $emails = preg_split('/[,\n\r]+/', $emails) ?: [];
        }

        if (!is_array($emails)) {
            return [];
        }

        $valid = [];
        foreach ($emails as $email) {
            $email = trim((string) $email);
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $valid[] = $email;
            }
        }

        return array_values(array_unique($valid));
    }

    protected function resolveEmployeeEmail(?int $employeeId): ?string
    {
        if (!$employeeId) {
            return null;
        }

        $employee = Employee::find($employeeId);
        if (!$employee || empty($employee->email)) {
            return null;
        }

        $email = trim($employee->email);

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    /**
     * @param  string|string[]  $to
     * @param  string[]  $cc
     * @return array{sent: bool, skipped: bool, message: string|null}
     */
    protected function sendMailable($mailable, $to, array $cc = []): array
    {
        try {
            $mail = Mail::to($to);
            if (!empty($cc)) {
                $mail->cc($cc);
            }
            $mail->send($mailable);

            return [
                'sent' => true,
                'skipped' => false,
                'message' => null,
            ];
        } catch (\Exception $e) {
            Log::error('Inventory email notification failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return [
                'sent' => false,
                'skipped' => false,
                'message' => 'Inventory saved but email failed: ' . $e->getMessage(),
            ];
        }
    }
}

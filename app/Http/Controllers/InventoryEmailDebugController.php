<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Services\InventoryMailer;
use Illuminate\Http\Request;

class InventoryEmailDebugController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check_role:admin');
    }

    public function index(InventoryMailer $mailer)
    {
        $title = 'Inventories';
        $subtitle = 'Email Notification Debug';
        $inventories = Inventory::with('employee', 'asset')
            ->orderBy('id', 'desc')
            ->limit(200)
            ->get(['id', 'inventory_no', 'employee_id', 'asset_id', 'model_asset']);
        $defaultCc = implode(', ', $mailer->defaultCc());
        $ccFromSavedSettings = $mailer->hasSavedCcSettings();

        return view('inventories.email_debug', compact(
            'title',
            'subtitle',
            'inventories',
            'defaultCc',
            'ccFromSavedSettings'
        ));
    }

    public function saveCc(Request $request, InventoryMailer $mailer)
    {
        $request->validate([
            'inventory_mail_cc' => 'nullable|string',
        ]);

        $saved = $mailer->saveCc($request->input('inventory_mail_cc', ''));
        $count = count($saved);
        $message = $count > 0
            ? "Production CC saved ({$count} address(es)). Used on inventory create/transfer."
            : 'Production CC cleared. Create/transfer emails will send without CC.';

        return redirect()
            ->route('inventories.emailDebug')
            ->with('success', $message);
    }

    public function preview(Request $request, InventoryMailer $mailer)
    {
        $request->validate([
            'type' => 'required|in:created,transferred',
            'inventory_id' => 'required|integer|exists:inventories,id',
            'from_inventory_id' => 'nullable|integer|exists:inventories,id',
        ]);

        $data = $mailer->previewData(
            $request->type,
            (int) $request->inventory_id,
            $request->from_inventory_id ? (int) $request->from_inventory_id : null
        );

        if ($request->type === 'transferred') {
            return view('emails.inventory_transferred', [
                'inventory' => $data['inventory'],
                'fromInventory' => $data['fromInventory'],
            ]);
        }

        return view('emails.inventory_created', [
            'inventory' => $data['inventory'],
        ]);
    }

    public function send(Request $request, InventoryMailer $mailer)
    {
        $request->validate([
            'type' => 'required|in:created,transferred',
            'inventory_id' => 'required|integer|exists:inventories,id',
            'from_inventory_id' => 'nullable|integer|exists:inventories,id',
            'mail_to' => 'required|string',
            'mail_cc' => 'nullable|string',
        ]);

        $result = $mailer->sendTest(
            $request->type,
            (int) $request->inventory_id,
            $request->mail_to,
            $request->mail_cc,
            $request->from_inventory_id ? (int) $request->from_inventory_id : null
        );

        if ($result['sent']) {
            return redirect()
                ->route('inventories.emailDebug')
                ->with('success', 'Test email sent successfully.');
        }

        return redirect()
            ->route('inventories.emailDebug')
            ->withInput()
            ->with('error', $result['message'] ?? 'Failed to send test email.');
    }
}

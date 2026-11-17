<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Inventory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class HeroHoldingController extends Controller
{
    public function show($heroEmployeeId)
    {
        if (!Str::isUuid($heroEmployeeId)) {
            return response()->json([
                'message' => 'Hero employee id must be a UUID.',
            ], 422);
        }

        $employee = Employee::where('hero_employee_id', $heroEmployeeId)->first();
        if (!$employee) {
            return response()->json(['data' => []]);
        }

        $current = Inventory::with(['asset', 'brand', 'project', 'department', 'location'])
            ->where('employee_id', $employee->id)
            ->where('transfer_status', 'Available')
            ->where('is_active', 1)
            ->orderByDesc('inventory_no')
            ->orderByDesc('id')
            ->get();

        $numbers = $current->pluck('inventory_no')->filter(function ($number) {
            return $number !== null && $number !== '';
        })->unique()->values();

        $history = Inventory::with('employee')
            ->whereIn('inventory_no', $numbers->all())
            ->orderBy('id')
            ->get()
            ->groupBy('inventory_no');

        $data = $current->map(function ($row) use ($history) {
            $events = ($row->inventory_no !== null && $row->inventory_no !== '')
                ? $history->get($row->inventory_no, collect())
                : collect([$row]);

            return [
                'inventory_no' => $row->inventory_no,
                'asset_name' => optional($row->asset)->asset_name,
                'serial_no' => $row->serial_no,
                'brand_name' => optional($row->brand)->brand_name,
                'model_asset' => $row->model_asset,
                'part_no' => $row->part_no,
                'quantity' => $row->quantity,
                'inventory_status' => $row->inventory_status,
                'transfer_status' => $row->transfer_status,
                'project_code' => optional($row->project)->project_code,
                'project_name' => optional($row->project)->project_name,
                'department_name' => optional($row->department)->dept_name,
                'location_name' => optional($row->location)->location_name,
                'date' => $this->displayDate($row->input_date),
                'input_date' => $row->input_date,
                'remarks' => $row->remarks,
                'history' => $events->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'holder_name' => optional($event->employee)->fullname,
                        'nik' => optional($event->employee)->nik !== null ? (string) $event->employee->nik : null,
                        'hero_employee_id' => optional($event->employee)->hero_employee_id,
                        'date' => $this->displayDate($event->input_date),
                        'input_date' => $event->input_date,
                        'quantity' => $event->quantity,
                        'inventory_status' => $event->inventory_status,
                        'transfer_status' => $event->transfer_status,
                    ];
                })->values(),
            ];
        })->values();

        return response()->json(['data' => $data]);
    }

    private function displayDate($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Exception $exception) {
            return null;
        }
    }
}

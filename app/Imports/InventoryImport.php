<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Asset;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\Department;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventoryImport implements ToModel, WithHeadingRow
{
    private $employees, $assets, $projects, $departments, $users;

    public function __construct()
    {
        $this->employees = Employee::select('id', 'nik', 'fullname')->get();
        $this->assets = Asset::select('id', 'asset_name')->get();
        $this->projects = Project::select('id', 'project_code')->get();
        $this->departments = Department::select('id', 'dept_name')->get();
        $this->users = User::select('id', 'name')->get();
    }

    public function model(array $row)
    {
        $employee = $this->employees->where('nik', $row['nik'])->where('fullname', $row['fullname'])->first();
        $asset = $this->assets->where('asset_name', $row['asset_name'])->first();
        $project = $this->projects->where('project_code', $row['project_code'])->first();
        $department = $this->departments->where('dept_name', $row['dept_name'])->first();
        $user = $this->users->where('name', $row['created_by'])->first();

        return new Inventory([
            'inventory_no' => $row['inventory_no'],
            'employee_id' => $employee->id ?? NULL,
            'asset_id' => $asset->id ?? NULL,
            'project_id' => $project->id ?? NULL,
            'department_id' => $department->id ?? NULL,
            'brand' => $row['brand'],
            'model_asset' => $row['model_asset'],
            'serial_no' => $row['serial_no'],
            'part_no' => $row['part_no'],
            'po_no' => $row['po_no'],
            'quantity' => $row['quantity'],
            'remarks' => $row['remarks'],
            'input_date' => Date::excelToDateTimeObject($row['input_date']),
            'created_by' => $user->id ?? NULL,
            'reference_no' => $row['reference_no'],
            'reference_date' => Date::excelToDateTimeObject($row['reference_date']) ?? '0000-00-00',
            'location' => $row['location'],
            'qrcode' => $row['qrcode'],
            'inventory_status' => $row['inventory_status'],
            'transfer_status' => $row['transfer_status'],
        ]);
    }
}

<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Asset;
use App\Models\Brand;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Inventory;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InventoryImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{

    use Importable, SkipsErrors, SkipsFailures;

    public $employee, $asset, $project, $department, $user, $brand, $location;

    public function __construct()
    {
        $this->employees = Employee::select('id', 'nik', 'fullname')->get();
        $this->assets = Asset::select('id', 'asset_name')->get();
        $this->projects = Project::select('id', 'project_code')->get();
        $this->departments = Department::select('id', 'dept_name')->get();
        $this->brands = Brand::select('id', 'brand_name')->get();
        $this->locations = Location::select('id', 'location_name')->get();
        $this->users = User::select('id', 'name')->get();
    }

    public function model(array $row)
    {
        $employee = $this->employees->where('nik', $row['nik'])->first();
        $asset = $this->assets->where('asset_name', $row['asset_name'])->first();
        $project = $this->projects->where('project_code', $row['project_code'])->first();
        $department = $this->departments->where('dept_name', $row['dept_name'])->first();
        $brand = $this->brands->where('brand_name', $row['brand_name'])->first();
        $location = $this->locations->where('location_name', $row['location_name'])->first();
        $user = $this->users->where('name', $row['created_by'])->first();

        // dd($row['nik'], $row['fullname'], $employee, $asset, $project, $department, $brand, $location, $user);

        $inventory = new Inventory();
        $inventory->id = $row['id'];
        $inventory->inventory_no = $row['inventory_no'];
        $inventory->employee_id = $employee->id ?? NULL;
        // $inventory->employee_id = $row['employee_id'];
        $inventory->asset_id = $asset->id ?? NULL;
        $inventory->project_id = $project->id ?? NULL;
        $inventory->department_id = $department->id ?? NULL;
        $inventory->brand_id = $brand->id ?? NULL;
        $inventory->model_asset = $row['model_asset'] ?? NULL;
        $inventory->serial_no = $row['serial_no'] ?? NULL;
        $inventory->part_no = $row['part_no'] ?? NULL;
        $inventory->po_no = $row['po_no'] ?? NULL;
        $inventory->quantity = $row['quantity'] ?? NULL;
        $inventory->remarks = $row['remarks'] ?? NULL;
        if ($row['input_date'] == NULL) {
            $inventory->input_date = NULL;
        } else {
            $inventory->input_date = Date::excelToDateTimeObject($row['input_date']);
        }
        $inventory->created_by = $user->id ?? NULL;
        $inventory->reference_no = $row['reference_no'] ?? NULL;
        if ($row['reference_date'] == NULL) {
            $inventory->reference_date = NULL;
        } else {
            $inventory->reference_date = Date::excelToDateTimeObject($row['reference_date']);
        }
        $inventory->location_id = $location->id ?? NULL;
        $inventory->qrcode = $row['qrcode'] ?? NULL;
        $inventory->inventory_status = $row['inventory_status'] ?? NULL;
        $inventory->transfer_status = $row['transfer_status'] ?? NULL;
        $inventory->is_active = $row['is_active'] ?? NULL;
        $inventory->save();
    }

    public function rules(): array
    {
        return [
            '*.id' => ['required', 'unique:inventories,id'],
            '*.nik' => ['required', 'exists:employees,nik'],
            '*.fullname' => ['required'],
            '*.asset_name' => ['required', 'exists:assets,asset_name'],
            // '*.project_code' => ['exists:projects,project_code'],
            // '*.brand_name' => ['exists:brands,brand_name'],
            // '*.model_asset' => ['required'],
            '*.quantity' => ['required'],
            '*.inventory_status' => ['required'],
            '*.transfer_status' => ['required'],
            // '*.location_name' => ['exists:locations,location_name'],
        ];
        // }
    }

    public function batchSize(): int
    {
        return 3000;
    }

    public function chunkSize(): int
    {
        return 3000;
    }
}

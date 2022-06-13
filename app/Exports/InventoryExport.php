<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class InventoryExport implements 
FromCollection, 
WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $inventory = Inventory::leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
                        ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
                        ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
                        ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
                        ->leftJoin('users', 'inventories.created_by', '=', 'users.id')
                        ->select(['inventory_no', 'employees.nik', 'employees.fullname', 'asset_name','project_code', 'dept_name', 'brand', 'model_asset', 'serial_no', 'part_no', 'po_no', 'quantity', 'remarks', 'input_date', 'users.name', 'reference_no','reference_date','location','qrcode','inventory_status','transfer_status'])
                        ->orderBy('inventories.inventory_no','desc')->skip(0)->take(10)->get();

        return $inventory;
    }

    public function headings(): array
    {
        return ['inventory_no', 'nik', 'fullname', 'asset_name', 'project_code', 'dept_name', 'brand', 'model_asset', 'serial_no', 'part_no', 'po_no', 'quantity', 'remarks', 'input_date', 'created_by', 'reference_no','reference_date', 'location', 'qrcode', 'inventory_status', 'transfer_status'];   
    }
}

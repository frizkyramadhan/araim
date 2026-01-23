<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class InventoryExport implements
    FromQuery,
    ShouldAutoSize,
    WithMapping,
    WithHeadings,
    WithTitle,
    WithColumnFormatting,
    WithColumnWidths
{
    use Exportable;

    public function title(): string
    {
        return 'inventory';
    }

    public function columnWidths(): array
    {
        return [
            'N' => 55
        ];
    }

    public function query()
    {
        $inventory = Inventory::leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
            ->leftJoin('departments', 'inventories.department_id', '=', 'departments.id')
            ->leftJoin('users', 'inventories.created_by', '=', 'users.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->leftJoin('locations', 'inventories.brand_id', '=', 'locations.id')
            ->select(['inventories.id', 'inventory_no', 'employees.nik', 'employees.fullname', 'asset_name', 'project_code', 'dept_name', 'categories.category_name', 'brand_name', 'model_asset', 'serial_no', 'part_no', 'po_no', 'quantity', 'remarks', 'input_date', 'users.name', 'reference_no', 'reference_date', 'location_name', 'qrcode', 'inventory_status', 'transfer_status', 'is_active'])
            ->orderBy('inventories.inventory_no', 'desc')
            ->orderBy('inventories.id', 'desc');

        return $inventory;
    }

    public function map($inventory): array
    {
        return [
            $inventory->id,
            $inventory->inventory_no,
            $inventory->nik,
            $inventory->fullname,
            $inventory->asset_name,
            $inventory->project_code,
            $inventory->dept_name,
            $inventory->category_name,
            $inventory->brand_name,
            $inventory->model_asset,
            $inventory->serial_no,
            $inventory->part_no,
            $inventory->po_no,
            $inventory->quantity == 0 ? '0' : $inventory->quantity,
            $inventory->remarks,
            $inventory->input_date ? Date::stringToExcel($inventory->input_date) : '',
            $inventory->name,
            $inventory->reference_no,
            $inventory->reference_date ? Date::stringToExcel($inventory->reference_date) : '',
            $inventory->location_name,
            $inventory->qrcode,
            $inventory->inventory_status,
            $inventory->transfer_status,
            $inventory->is_active == 0 ? '0' : '1',
        ];
    }

    public function headings(): array
    {
        return ['id', 'inventory_no', 'nik', 'fullname', 'asset_name', 'project_code', 'dept_name', 'category_name', 'brand_name', 'model_asset', 'serial_no', 'part_no', 'po_no', 'quantity', 'remarks', 'input_date', 'created_by', 'reference_no', 'reference_date', 'location_name', 'qrcode', 'inventory_status', 'transfer_status', 'is_active'];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'R' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}

<?php

namespace App\Imports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BrandImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    public function headingRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // if id exists, update
        if (Brand::find($row['id'])) {
            $brand = Brand::find($row['id']);
            $brand->brand_name = $row['brand_name'];
            $brand->brand_status = $row['brand_status'];
            $brand->save();
        } else {
            $brand = new Brand();
            $brand->id = $row['id'];
            $brand->brand_name = $row['brand_name'];
            $brand->brand_status = $row['brand_status'];
            $brand->save();
        }
    }

    public function rules(): array
    {
        if (Brand::find(['*.id'])) {
            return [
                '*.id' => ['required'],
                '*.brand_name' => ['required'],
                '*.brand_status' => ['required']
            ];
        } else {
            return [
                '*.id' => ['required', 'unique:brands,id'],
                '*.brand_name' => ['required'],
                '*.brand_status' => ['required']
            ];
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

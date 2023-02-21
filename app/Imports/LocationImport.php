<?php

namespace App\Imports;

use App\Models\Location;
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

class LocationImport implements
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
        if (Location::find($row['id'])) {
            $location = Location::find($row['id']);
            $location->location_name = $row['location_name'];
            $location->location_status = $row['location_status'];
            $location->save();
        } else {
            $location = new Location();
            $location->location_name = $row['location_name'];
            $location->location_status = $row['location_status'];
            $location->save();
        }
    }

    public function rules(): array
    {
        if (Location::find(['*.id'])) {
            return [
                '*.id' => ['required'],
                '*.location_name' => ['required'],
                '*.location_status' => ['required']
            ];
        } else {
            return [
                '*.id' => ['required', 'unique:locations,id'],
                '*.location_name' => ['required'],
                '*.location_status' => ['required']
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

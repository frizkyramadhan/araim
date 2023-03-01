<?php

namespace App\Models;

use App\Models\Asset;
use App\Models\Image;
use App\Models\Brand;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Department;
use App\Models\Specification;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'properties' => 'collection' // casting the JSON database column
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    public function specification()
    {
        return $this->hasMany(Specification::class);
    }

    public function bast()
    {
        return $this->hasMany(Bast::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Inventory')
            // ->logAll()
            ->logOnly(['id', 'inventory_no', 'employee.fullname', 'asset.asset_name', 'project.project_code', 'project.project_name', 'department.dept_name', 'brand.brand_name', 'model_asset', 'serial_no', 'part_no', 'po_no', 'quantity', 'remarks', 'input_date', 'reference_no', 'reference_date', 'location.location_name', 'inventory_status', 'transfer_status', 'is_active', 'created_at', 'updated_at'])
            // ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

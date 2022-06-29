<?php

namespace App\Models;

use App\Models\Project;
use App\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    // public function receive()
    // {
    //     return $this->hasMany(Bast::class, 'bast_receive');
    // }

    // public function submit()
    // {
    //     return $this->hasMany(Bast::class, 'bast_submit');
    // }
}

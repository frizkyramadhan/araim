<?php

namespace App\Models;

use App\Models\Position;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function position()
    {
        return $this->hasMany(Position::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}

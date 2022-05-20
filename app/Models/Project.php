<?php

namespace App\Models;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}

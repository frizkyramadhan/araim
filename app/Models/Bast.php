<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bast extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function receive()
    {
        return $this->belongsTo(Employee::class, 'bast_receive', 'id');
    }

    public function submit()
    {
        return $this->belongsTo(Employee::class, 'bast_submit', 'id');
    }
}

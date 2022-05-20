<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}

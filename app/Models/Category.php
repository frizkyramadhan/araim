<?php

namespace App\Models;

use App\Models\Assets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assets()
    {
        return $this->hasMany(Assets::class);
    }
}

<?php

namespace App\Models;

use App\Models\Specification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Component extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function specification()
    {
        return $this->hasMany(Specification::class);
    }
}

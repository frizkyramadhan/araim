<?php

namespace App\Models;

use App\Models\User;
use App\Models\Assets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function asset()
    {
        return $this->hasMany(Assets::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

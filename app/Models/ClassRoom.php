<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Branch extends Model
{
    protected $fillable = ['name'];

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class);
    }

    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            Classe::class,
            'branch_id', // Foreign key on classes table
            'id', // Foreign key on students table
            'id', // Local key on branches table
            'id'  // Local key on classes table
        )->distinct();
    }

    public function calculateGrade($studentId): float
    {
        $totalFactor = $this->modules->sum('factor');
        $weightedSum = $this->modules->sum(function ($module) use ($studentId) {
            return $module->calculateGrade($studentId) * $module->factor;
        });

        return $totalFactor > 0 ? $weightedSum / $totalFactor : 0;
    }
} 
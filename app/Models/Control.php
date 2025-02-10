<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Control extends Model
{
    protected $fillable = ['name', 'type', 'factor', 'max_grade'];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function calculateGrade($studentId): float
    {
        $grade = $this->grades()
            ->where('student_id', $studentId)
            ->first();

        if (!$grade) {
            return 0;
        }

        return ($grade->grade_value / $this->max_grade) * 100;
    }
} 
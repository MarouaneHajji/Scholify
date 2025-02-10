<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    protected $fillable = [
        'name',
        'branch_id',
        'factor'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function controls(): HasMany
    {
        return $this->hasMany(Control::class);
    }

    public function calculateGrade($studentId): float
    {
        $totalFactor = $this->controls->sum('factor');
        $weightedSum = $this->controls->sum(function ($control) use ($studentId) {
            $grade = $control->grades()->where('student_id', $studentId)->first();
            return $grade ? $grade->weighted_grade : 0;
        });

        return $totalFactor > 0 ? $weightedSum / $totalFactor : 0;
    }

    public function calculateStudentGrade($studentId)
    {
        $totalFactor = $this->controls->sum('factor');
        $grades = Grade::whereIn('control_id', $this->controls->pluck('id'))
            ->where('student_id', $studentId)
            ->get();
        
        $weightedSum = $grades->sum(function ($grade) {
            return $grade->weighted_grade;
        });

        return $totalFactor > 0 ? ($weightedSum / $totalFactor) * $this->factor : 0;
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_teacher')
                    ->withPivot('teacher_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'classe_teacher')
                    ->withPivot('classe_id');
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'control_id',
        'grade_value',
        'grade_name',
        'grade_image',
        'correction_file'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function control(): BelongsTo
    {
        return $this->belongsTo(Control::class);
    }

    // Helper method to calculate weighted grade
    public function getWeightedGradeAttribute()
    {
        return ($this->grade_value / $this->control->max_grade) * $this->control->factor;
    }
} 
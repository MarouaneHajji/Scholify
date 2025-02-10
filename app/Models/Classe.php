<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $fillable = [
        'name',
        'branch_id',
    ];

    // Change to many-to-many relationship
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'classe_teacher')
            ->withPivot('module_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student')
            ->withTimestamps();
    }

    // Add this relationship if not already present
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'classe_teacher')
                    ->withPivot('teacher_id');
    }
}
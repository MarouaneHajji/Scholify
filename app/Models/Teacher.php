<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'profile_picture'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_teacher', 'teacher_id', 'classe_id')
            ->withPivot('module_id');
    }

    /**
     * Get the modules that this teacher teaches.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'classe_teacher', 'teacher_id', 'module_id')
            ->withPivot('classe_id');
    }

    /**
     * Get the teacher's full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
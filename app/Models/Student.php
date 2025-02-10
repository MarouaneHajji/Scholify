<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'date_of_birth',
        'address',
        'extra_info',
        'profile_picture',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'class_student')
            ->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_student')
            ->withTimestamps();
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'teacher', 'admin', etc.
        'profile_picture'
    ];

   
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Define the relationship with the Teacher model
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

           
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }
        
    public function isAdmin()
            {
                return $this->role === 'admin';
            }
   

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Remove or comment out this relationship since we're using the one through Teacher model
    // public function teacherClasses()
    // {
    //     return $this->hasMany(Classes::class, 'teacher_id');
    // }
}

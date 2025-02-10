<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'day',
        'time',
        'module',
        'teacher',
        'room'
    ];

    public function schedule()
    {
        \Log::info('Accessing schedule relationship');
        return $this->belongsTo(Schedule::class);
    }

    public function teacher()
    {
        \Log::info('Accessing teacher relationship');
        return $this->belongsTo(Teacher::class);
    }

    public function module()
    {
        \Log::info('Accessing module relationship');
        return $this->belongsTo(Module::class);
    }
} 
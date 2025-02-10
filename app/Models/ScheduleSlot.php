<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'teacher_id',
        'module_id',
        'room',
        'day',
        'start_time',
        'end_time'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
} 
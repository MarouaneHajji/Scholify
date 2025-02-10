<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['class_id'];

    public function class()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function slots()
    {
        \Log::info('Accessing slots relationship');
        return $this->hasMany(ScheduleSlot::class);
    }

    public function modules()
    {
        return $this->hasManyThrough(Module::class, ScheduleSlot::class);
    }

    public function getSlotsByDay()
    {
        return $this->slots->groupBy('day')->map(function($slots) {
            return $slots->map(function($slot) {
                return [
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'teacher_id' => $slot->teacher_id,
                    'module_id' => $slot->module_id,
                    'room' => $slot->room
                ];
            })->values();
        })->toArray();
    }
}

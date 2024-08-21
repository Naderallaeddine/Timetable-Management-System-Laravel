<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;
    protected $fillable = [
        'classroom_id',
        'teacher_id',
        'subject_id',
        'start_time',
        'end_time',
        'day_of_week',
    ];

    /**
     * Get the classroom associated with the timetable.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the teacher associated with the timetable.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the subject associated with the timetable.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}

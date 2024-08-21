<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeacherSubjectClass extends Pivot
{
    use HasFactory;

    protected $table = 'teacher_subject_class';
    protected $fillable = ['teacher_id', 'subject_id', 'classroom_id', 'weekly_sessions'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}

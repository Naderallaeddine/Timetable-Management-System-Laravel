<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'grade',
    ];
    public function teachersSubjects()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject_class')
                    ->using(TeacherSubjectClass::class)
                    ->withPivot('subject_id', 'weekly_sessions')
                    ->withTimestamps();
    }
}


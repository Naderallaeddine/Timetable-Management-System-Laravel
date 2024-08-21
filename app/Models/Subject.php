<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    public function teachersClasses()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject_class')
                    ->using(TeacherSubjectClass::class)
                    ->withPivot('classroom_id', 'weekly_sessions')
                    ->withTimestamps();
    }
}

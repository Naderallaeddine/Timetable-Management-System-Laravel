<?php

namespace App\Http\Controllers;

use App\Models\TeacherSubjectClass;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;


class TeacherSubjectClassController extends Controller
{
    public function index()
    {
        $teacherSubjectClasses = TeacherSubjectClass::with('teacher', 'subject', 'classroom')->get();
        return view('teacher_subject_classes.index', compact('teacherSubjectClasses'));
    }

    public function create()
    {
        $teachers = Teacher::all();
        $subjects = Subject::all();
        $classrooms = Classroom::all();
        return view('teacher_subject_classes.create', compact('teachers', 'subjects', 'classrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'weekly_sessions' => 'required|integer',
        ]);

        TeacherSubjectClass::create($request->all());

        return redirect()->route('teacher_subject_classes.index')
            ->with('success', 'Teacher-Subject-Class relationship created successfully.');
    }

    public function show(TeacherSubjectClass $teacherSubjectClass)
    {
        return view('teacher_subject_classes.show', compact('teacherSubjectClass'));
    }

    public function edit(TeacherSubjectClass $teacherSubjectClass)
    {
        $teachers = Teacher::all();
        $subjects = Subject::all();
        $classrooms = Classroom::all();
        return view('teacher_subject_classes.edit', compact('teacherSubjectClass', 'teachers', 'subjects', 'classrooms'));
    }

    public function update(Request $request, TeacherSubjectClass $teacherSubjectClass)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'weekly_sessions' => 'required|integer',
        ]);

        $teacherSubjectClass->update($request->all());

        return redirect()->route('teacher_subject_classes.index')
            ->with('success', 'Teacher-Subject-Class relationship updated successfully.');
    }

    public function destroy(TeacherSubjectClass $teacherSubjectClass)
    {
        $teacherSubjectClass->delete();

        return redirect()->route('teacher_subject_classes.index')
            ->with('success', 'Teacher-Subject-Class relationship deleted successfully.');
    }
}

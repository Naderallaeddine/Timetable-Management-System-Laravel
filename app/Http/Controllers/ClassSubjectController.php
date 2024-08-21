<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;

class ClassSubjectController extends Controller
{
    public function index()
    {
        $classSubjects = ClassSubject::with('classroom', 'subject')->get();
        return view('class_subjects.index', compact('classSubjects'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        $subjects = Subject::all();
        return view('class_subjects.create', compact('classrooms', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'required_sessions' => 'required|integer',
        ]);

        ClassSubject::create($request->all());

        return redirect()->route('class_subjects.index')
            ->with('success', 'Class-Subject relationship created successfully.');
    }

    public function show(ClassSubject $classSubject)
    {
        return view('class_subjects.show', compact('classSubject'));
    }

    public function edit(ClassSubject $classSubject)
    {
        $classrooms = Classroom::all();
        $subjects = Subject::all();
        return view('class_subjects.edit', compact('classSubject', 'classrooms', 'subjects'));
    }

    public function update(Request $request, ClassSubject $classSubject)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'required_sessions' => 'required|integer',
        ]);

        $classSubject->update($request->all());

        return redirect()->route('class_subjects.index')
            ->with('success', 'Class-Subject relationship updated successfully.');
    }

    public function destroy(ClassSubject $classSubject)
    {
        $classSubject->delete();

        return redirect()->route('class_subjects.index')
            ->with('success', 'Class-Subject relationship deleted successfully.');
    }
}

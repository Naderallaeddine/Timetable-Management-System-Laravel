@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Edit Teacher-Subject-Class Relationship</h1>
    <form action="{{ route('teacher_subject_classes.update', $teacherSubjectClass) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="teacher_id">Teacher</label>
            <select name="teacher_id" id="teacher_id" class="form-control" required>
                @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ $teacher->id == $teacherSubjectClass->teacher_id ? 'selected' : '' }}>
                    {{ $teacher->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="subject_id">Subject</label>
            <select name="subject_id" id="subject_id" class="form-control" required>
                @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}" {{ $subject->id == $teacherSubjectClass->subject_id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="classroom_id">Class</label>
            <select name="classroom_id" id="classroom_id" class="form-control" required>
                @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}" {{ $classroom->id == $teacherSubjectClass->classroom_id ? 'selected' : '' }}>
                    {{ $classroom->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="weekly_sessions">Weekly Sessions</label>
            <input type="number" name="weekly_sessions" id="weekly_sessions" class="form-control" value="{{ $teacherSubjectClass->weekly_sessions }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

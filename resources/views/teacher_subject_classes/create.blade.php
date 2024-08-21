@extends('layouts.layout')
@section('content')
<div class="container">
    <h1>Add New Teacher-Subject-Class Relationship</h1>
    <form action="{{ route('teacher_subject_classes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="teacher_id">Teacher</label>
            <select name="teacher_id" id="teacher_id" class="form-control" required>
                @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="subject_id">Subject</label>
            <select name="subject_id" id="subject_id" class="form-control" required>
                @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="classroom_id">Class</label>
            <select name="classroom_id" id="classroom_id" class="form-control" required>
                @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="weekly_sessions">Weekly Sessions</label>
            <input type="number" name="weekly_sessions" id="weekly_sessions" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection

@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Teacher-Subject-Class Relationship Details</h1>
    <p><strong>Teacher:</strong> {{ $teacherSubjectClass->teacher->name }}</p>
    <p><strong>Subject:</strong> {{ $teacherSubjectClass->subject->name }}</p>
    <p><strong>Class:</strong> {{ $teacherSubjectClass->classroom->name }}</p>
    <p><strong>Weekly Sessions:</strong> {{ $teacherSubjectClass->weekly_sessions }}</p>
    <a href="{{ route('teacher_subject_classes.index') }}" class="btn btn-primary">Back to List</a>
</div>
@endsection

@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Class-Subject Relationship Details</h1>
    <p><strong>Class:</strong> {{ $classSubject->classroom->name }}</p>
    <p><strong>Subject:</strong> {{ $classSubject->subject->name }}</p>
    <p><strong>Required Sessions:</strong> {{ $classSubject->required_sessions }}</p>
    <a href="{{ route('class_subjects.index') }}" class="btn btn-primary">Back to List</a>
</div>
@endsection


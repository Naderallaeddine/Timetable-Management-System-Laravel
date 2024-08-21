@extends('layouts.layout')


@section('content')
<div class="container">
    <h1>Edit Class-Subject Relationship</h1>
    <form action="{{ route('class_subjects.update', $classSubject) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="classroom_id">Class</label>
            <select name="classroom_id" id="classroom_id" class="form-control" required>
                @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}" {{ $classroom->id == $classSubject->classroom_id ? 'selected' : '' }}>
                    {{ $classroom->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="subject_id">Subject</label>
            <select name="subject_id" id="subject_id" class="form-control" required>
                @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}" {{ $subject->id == $classSubject->subject_id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="required_sessions">Required Sessions</label>
            <input type="number" name="required_sessions" id="required_sessions" class="form-control" value="{{ $classSubject->required_sessions }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

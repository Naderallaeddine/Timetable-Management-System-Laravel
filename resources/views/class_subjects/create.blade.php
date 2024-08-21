@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Add New Class-Subject Relationship</h1>
    <form action="{{ route('class_subjects.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="classroom_id">Class</label>
            <select name="classroom_id" id="classroom_id" class="form-control" required>
                @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
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
            <label for="required_sessions">Required Sessions</label>
            <input type="number" name="required_sessions" id="required_sessions" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection

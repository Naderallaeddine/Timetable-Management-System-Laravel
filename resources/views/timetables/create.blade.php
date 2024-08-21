@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Create Timetable</h1>
    <div class="container mt-4">
        @if($errors->has('conflict_error'))
            <div class="alert alert-danger">
                {{ $errors->first('conflict_error') }}
            </div>
        @endif
    </div>

    <form action="{{ route('timetables.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="classroom_id">Classroom</label>
            <select id="classroom_id" name="classroom_id" class="form-control">
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="teacher_id">Teacher</label>
            <select id="teacher_id" name="teacher_id" class="form-control">
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="subject_id">Subject</label>
            <select id="subject_id" name="subject_id" class="form-control">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="day_of_week">Day of the Week</label>
            <select id="day_of_week" name="day_of_week" class="form-control">
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
            </select>
        </div>
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" id="start_time" name="start_time" class="form-control">
        </div>
        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="time" id="end_time" name="end_time" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create Timetable</button>
    </form>
</div>
@endsection

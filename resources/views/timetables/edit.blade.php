@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Edit Timetable</h1>
    <div class="container mt-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <form action="{{ route('timetables.update', $timetable->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="classroom_id">Classroom</label>
            <select id="classroom_id" name="classroom_id" class="form-control">
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $classroom->id == $timetable->classroom_id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="teacher_id">Teacher</label>
            <select id="teacher_id" name="teacher_id" class="form-control">
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ $teacher->id == $timetable->teacher_id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="subject_id">Subject</label>
            <select id="subject_id" name="subject_id" class="form-control">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $subject->id == $timetable->subject_id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="day_of_week">Day of the Week</label>
            <select id="day_of_week" name="day_of_week" class="form-control">
                <option value="Monday" {{ $timetable->day_of_week == 'Monday' ? 'selected' : '' }}>Monday</option>
                <option value="Tuesday" {{ $timetable->day_of_week == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                <option value="Wednesday" {{ $timetable->day_of_week == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                <option value="Thursday" {{ $timetable->day_of_week == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                <option value="Friday" {{ $timetable->day_of_week == 'Friday' ? 'selected' : '' }}>Friday</option>
            </select>
        </div>
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" id="start_time" name="start_time" class="form-control" value="{{ $timetable->start_time }}">
        </div>
        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="time" id="end_time" name="end_time" class="form-control" value="{{ $timetable->end_time }}">
        </div>
        <button type="submit" class="btn btn-warning">Update Timetable</button>
    </form>

    <form action="{{ route('timetables.destroy', $timetable->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
    </form>
</div>
@endsection

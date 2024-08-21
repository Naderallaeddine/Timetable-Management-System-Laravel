@extends('layouts.layout')

@section('content')
<div class="container">
    <h1 class="mb-4">Timetable Management</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Generate Timetables for All Classrooms -->

    <div class="card-body d-flex align-items-center">
        <form action="{{ route('timetables.generateAll') }}" method="POST" class="custom-padding">
            @csrf
            <button type="submit" class="btn btn-primary">Generate All Timetables</button>
        </form>

        <form action="{{ route('timetables.index') }}" method="GET">
            @csrf
            <button type="submit" class="btn btn-secondary">All Timetables</button>
        </form>

        <form action="{{ route('export.pdf') }}" method="GET" class="custom-padding">
            @csrf
            <button type="submit" class="btn btn-danger">Export to PDF</button>
        </form>

    </div>

<form action="{{ route('timetables.teacherSchedule') }}" method="POST" >
    @csrf
    <div class="card-body">
        <label for="teacher">Select Teacher:</label>
        <select name="teacherId" id="teacher" class="form-control">
            <option value="" disabled selected>Select a Teacher</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-info ml-4">View Schedule</button>
</form>



    <!-- Select a Classroom -->
    <div class="card-body">
        <form action="{{ route('timetables.index') }}" method="GET">
            <div class="form-group">
                <label for="classroom">Select Classroom:</label>
                <select name="classroom_id" id="classroom" class="form-control">
                    <option value="">-- All Classrooms --</option>
                    @foreach($classrooms as $classroomOption)
                        <option value="{{ $classroomOption->id }}" {{ request('classroom_id') == $classroomOption->id ? 'selected' : '' }}>
                            {{ $classroomOption->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">View Timetable</button>
        </form>
    </div>

    <!-- Display Timetables for Selected Classroom -->
    @if(request('classroom_id'))
        @php
            $selectedClassroom = $classrooms->firstWhere('id', request('classroom_id'));
        @endphp
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h3>{{ $selectedClassroom->name }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered timetable-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            @foreach(['08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-01:00'] as $time)
                                <th>{{ $time }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                            <tr>
                                <td>{{ $day }}</td>
                                @foreach(['08:00:00', '09:00:00', '10:00:00', '11:00:00', '12:00:00'] as $startTime)
                                    <td class="timetable-cell">
                                        @php
                                            $timetableEntry = $timetables->first(function ($entry) use ($day, $startTime, $selectedClassroom) {
                                                return $entry->day_of_week == $day && $entry->start_time == $startTime && $entry->classroom_id == $selectedClassroom->id;
                                            });
                                        @endphp
                                        <a href="{{ $timetableEntry ? route('timetables.edit', $timetableEntry->id) : route('timetables.create', ['day_of_week' => $day, 'start_time' => $startTime, 'classroom_id' => $selectedClassroom->id]) }}" class="timetable-link">
                                            @if($timetableEntry)
                                                <span class="timetable-text">{{ $timetableEntry->subject->name }}<br>{{ $timetableEntry->teacher->name }}</span>
                                            @else
                                                <span class="timetable-text"><br><br></span>
                                            @endif
                                        </a>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Display Timetables for All Classrooms -->
        @foreach ($classrooms as $classroom)
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h3>{{ $classroom->name }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered timetable-table">
                        <thead>
                            <tr>
                                <th>Day</th>
                                @foreach(['08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-01:00'] as $time)
                                    <th>{{ $time }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                <tr>
                                    <td>{{ $day }}</td>
                                    @foreach(['08:00:00', '09:00:00', '10:00:00', '11:00:00', '12:00:00'] as $startTime)
                                        <td class="timetable-cell">
                                            @php
                                                $timetableEntry = $timetables->first(function ($entry) use ($day, $startTime, $classroom) {
                                                    return $entry->day_of_week == $day && $entry->start_time == $startTime && $entry->classroom_id == $classroom->id;
                                                });
                                            @endphp
                                            <a href="{{ $timetableEntry ? route('timetables.edit', $timetableEntry->id) : route('timetables.create', ['day_of_week' => $day, 'start_time' => $startTime, 'classroom_id' => $classroom->id]) }}" class="timetable-link">
                                                @if($timetableEntry)
                                                    <span class="timetable-text">{{ $timetableEntry->subject->name }}<br>{{ $timetableEntry->teacher->name }}</span>
                                                @else
                                                    <span class="timetable-text"><br><br></span>
                                                @endif
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>

<style>
    .timetable-table {
        margin-top: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .timetable-cell {
        background-color: #f9f9f9;
        padding: 20px;
        text-align: center;
        vertical-align: middle;
        transition: background-color 0.3s ease;
    }

    .timetable-cell:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }

    .timetable-link {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
        width: 100%;
    }

    .timetable-text {
        color: #333;
        font-size: 14px;
        line-height: 1.5;
    }
    .custom-padding {
            padding-right: 10px;
            padding-left: 10px;
        }
</style>
@endsection

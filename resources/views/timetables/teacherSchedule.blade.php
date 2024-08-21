@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Schedule for {{ $teacher->name }}</h1>
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
                                $timetableEntry = $timetables->first(function ($entry) use ($day, $startTime) {
                                    return $entry->day_of_week == $day && $entry->start_time == $startTime;
                                });
                            @endphp
                            <a href="{{ $timetableEntry ? route('timetables.edit', $timetableEntry->id) : route('timetables.create', ['day_of_week' => $day, 'start_time' => $startTime, 'teacher_id' => $teacher->id]) }}" class="timetable-link">
                                @if($timetableEntry)
                                    <span class="timetable-text">{{ $timetableEntry->subject->name }}<br>{{ $timetableEntry->classroom->name }}</span>
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

<style>
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .timetable-table {
        width: 100%;
        border-collapse: collapse;
    }

    .timetable-table th, .timetable-table td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: center;
    }

    .timetable-cell {
        position: relative;
    }

    .timetable-link {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .timetable-text {
        display: block;
        margin: auto;
        line-height: 1.2;
    }
</style>
@endsection

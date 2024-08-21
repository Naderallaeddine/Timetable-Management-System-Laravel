<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Classrooms Timetable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            color: #333;
        }
        h3{
            text-align: center;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        .classroom-header {
            background-color: #333;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h1>All Classrooms Timetable</h1>

    @foreach ($classrooms as $classroom)
        <div class="classroom-header">
            <h3>{{ $classroom->name }}</h3>
        </div>

        <table>
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
                            <td>
                                @php
                                    $timetableEntry = $timetables->first(function ($entry) use ($day, $startTime, $classroom) {
                                        return $entry->day_of_week == $day && $entry->start_time == $startTime && $entry->classroom_id == $classroom->id;
                                    });
                                @endphp
                                @if($timetableEntry)
                                    {{ $timetableEntry->subject->name }}<br>{{ $timetableEntry->teacher->name }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>

@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Assign teacher</h1>
    <a href="{{ route('teacher_subject_classes.create') }}" class="btn btn-primary mb-3">Assign teacher</a>
    @if($teacherSubjectClasses->isEmpty())
        <p>No teacher available.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Teacher</th>
                    <th>Subject</th>
                    <th>Weekly session</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teacherSubjectClasses as $teacherSubjectClass)
                    <tr>
                        <td>{{ $teacherSubjectClass->classroom->name }}</td>
                        <td>{{ $teacherSubjectClass->teacher->name }}</td>
                        <td>{{ $teacherSubjectClass->subject->name }}</td>
                        <td>{{ $teacherSubjectClass->weekly_sessions }}</td>

                        <td>
                            <a href="{{ route('teacher_subject_classes.show', $teacherSubjectClass->id) }}" class="btn btn-primary btn-sm">show</a>
                            <a href="{{ route('teacher_subject_classes.edit', $teacherSubjectClass->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('teacher_subject_classes.destroy', $teacherSubjectClass->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

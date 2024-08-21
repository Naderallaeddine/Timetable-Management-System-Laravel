@extends('layouts.layout')


@section('content')
<div class="container">
    <h1>Class-Subject Relationships</h1>
    <a href="{{ route('class_subjects.create') }}" class="btn btn-primary mb-3">Add New</a>
    <table class="table">
        <thead>
            <tr>
                <th>Class</th>
                <th>Subject</th>
                <th>Required Sessions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classSubjects as $classSubject)
            <tr>
                <td>{{ $classSubject->classroom->name }}</td>
                <td>{{ $classSubject->subject->name }}</td>
                <td>{{ $classSubject->required_sessions }}</td>
                <td>
                    <a href="{{ route('class_subjects.show', $classSubject->id) }}" class="btn btn-primary btn-sm">show</a>
                    <a href="{{ route('class_subjects.edit', $classSubject) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('class_subjects.destroy', $classSubject) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@extends('layouts.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h1>CLassroom</h1>
        <a href="{{ route('classrooms.create') }}" class="btn btn-primary">Add New Classroom</a>
    </div>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classrooms as $classroom)
                <tr>
                    <td>{{ $classroom->name }}</td>
                    <td>{{ $classroom->grade }}</td>
                    <td>
                        <a href="{{ route('classrooms.show', $classroom->id) }}" class="btn btn-primary btn-sm">show</a>
                        <a href="{{ route('classrooms.edit', $classroom->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('classrooms.destroy', $classroom->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

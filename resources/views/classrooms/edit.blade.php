@extends('layouts.layout')

@section('content')
    <h1>Edit classroom</h1>
    <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $classroom->name }}" required>
        </div>
        <div class="form-group">
            <label for="grade">Grade</label>
            <input type="text" class="form-control" id="grade" name="grade" value="{{ $classroom->grade }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Classroom</button>
    </form>
@endsection

@extends('layouts.layout')

@section('content')
    <h1>Add New Classroom</h1>
    <form action="{{ route('classrooms.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="grade">Grade</label>
            <input type="text" class="form-control" id="grade" name="grade" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Classroom</button>
    </form>
@endsection

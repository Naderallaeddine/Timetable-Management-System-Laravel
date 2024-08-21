@extends('layouts.layout')

@section('content')
    <h1>Edit Teacher</h1>
    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $teacher->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $teacher->email }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Teacher</button>
    </form>
@endsection

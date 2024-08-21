@extends('layouts.layout')

@section('content')
    <h1>Edit subject</h1>
    <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $subject->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" id="description" name="description" value="{{ $subject->description }}" >
        </div>
        <button type="submit" class="btn btn-primary">Update Subject</button>
    </form>
@endsection

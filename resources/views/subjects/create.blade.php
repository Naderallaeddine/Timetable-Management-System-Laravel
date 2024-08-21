@extends('layouts.layout')

@section('content')
    <h1>Add New Subject</h1>
    <form action="{{ route('subjects.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" id="description" name="description" >
        </div>
        <button type="submit" class="btn btn-primary">Add Subject</button>
    </form>
@endsection

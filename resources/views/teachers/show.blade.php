@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Teacher Details</h1>

    <div class="card">
        <div class="card-header">
            {{ $teacher->name }}
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> {{ $teacher->email }}</p>
           
        </div>
    </div>

    <a href="{{ route('teachers.index') }}" class="btn btn-primary mt-3">Back to Teachers</a>
</div>
@endsection

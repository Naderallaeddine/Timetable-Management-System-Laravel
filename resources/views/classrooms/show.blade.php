@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Classroom Details</h1>

    <div class="card">
        <div class="card-header">
            {{ $classroom->name }}
        </div>
        <div class="card-body">
            <p><strong>Grade:</strong> {{ $classroom->grade }}</p>

        </div>
    </div>

    <a href="{{ route('classrooms.index') }}" class="btn btn-primary mt-3">Back to Classrooms</a>
</div>
@endsection

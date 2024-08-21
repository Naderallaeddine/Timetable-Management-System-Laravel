@extends('layouts.layout')


@section('content')
<div class="container">
    <h1>Subject Details</h1>

    <div class="card">
        <div class="card-header">
            {{ $subject->name }}
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $subject->description }}</p>

        </div>
    </div>

    <a href="{{ route('subjects.index') }}" class="btn btn-primary mt-3">Back to Subjects</a>
</div>
@endsection

@extends('layouts.main')

@section('title', 'Generated Text')

@section('content')
<div class="container mt-5">
    <h1>Generated Text</h1>
    <p>{{ $text }}</p>
    <a href="{{ route('generate.form') }}" class="btn btn-primary mt-3">Go Back</a>
</div>
@endsection

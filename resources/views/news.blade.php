@extends('layouts.main')

@section('title', 'Generated Text')

@section('content')
<div class="container mt-5">
    <h1>News Results for '{{ request('keyword') }}' in {{ request('year') }}</h1>

    @if (count($newsData) > 0)
        <ul class="list-group">
            @foreach ($newsData as $news)
                <li class="list-group-item">{{ $news }}</li>
            @endforeach
        </ul>
    @else
        <p>No news articles found for the given keyword and year.</p>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back to Search</a>
</div>



@endsection

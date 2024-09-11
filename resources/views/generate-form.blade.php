@extends('layouts.main')

@section('title', 'Generate Text from News')

@section('content')
    <div class="container mt-5">
        <h1>Generate Text from News Scraping</h1>
        <form action="{{ route('generate.text') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="keyword">Enter Keyword</label>
                <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Enter keyword (e.g., ekonomi, politik)" required>
            </div>
            <div class="form-group">
                <label for="year">Select Year</label>
                <select name="year" id="year" class="form-control" required>
                    <option value="">Choose a Year</option>
                    @for ($i = date('Y'); $i >= 2015; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Generate Text</button>
        </form>
    </div>
@endsection

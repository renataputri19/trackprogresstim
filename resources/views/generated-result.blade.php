@extends('layouts.main')

@section('title', 'Generated Text')

@section('content')
    {{-- <div class="container mt-5">
        <h1>Generated Text</h1>
        
        <!-- Display the GPT-4 generated text -->
        <div class="mb-4">
            <h3>ChatGPT Summary:</h3>
            <div>
                {!! nl2br(e($text)) !!} <!-- Converts new lines to <br> and renders the text -->
            </div>
        </div>

        <!-- Display the scraped news articles -->
        <div class="mb-4">
            <h3>Original Scraped News Articles:</h3>
            @if(!empty($newsArticles) && count($newsArticles) > 0)
                <ul>
                    @foreach($newsArticles as $article)
                        <li>
                            <strong>{{ $article['title'] }}</strong><br>
                            {{ $article['description'] }}<br>
                            <a href="{{ $article['link'] }}" target="_blank">Read more</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No news articles found for the given keyword and year.</p>
            @endif

        <a href="{{ route('generate.form') }}" class="btn btn-primary mt-3">Go Back</a>
    </div> --}}


    <h1>Scraped News Articles</h1>

    @if(count($news) > 0)
        <ul>
            @foreach($news as $article)
                <li>
                    <a href="{{ $article['link'] }}" target="_blank">{{ $article['title'] }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No news articles found for the given keyword and year.</p>
    @endif
    
    <a href="{{ route('generate.form') }}" class="btn btn-secondary">Go Back</a>
    



@endsection

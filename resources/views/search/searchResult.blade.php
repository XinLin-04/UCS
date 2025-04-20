@extends('layouts.header')

@section('content')
    <div class="container my-5">
        <h1>Search Results for "{{ $query }}"</h1>

        @if ($posts->isEmpty())
            <p>No posts found matching your search query.</p>
        @else
            <div class="list-group">
                @foreach ($posts as $post)
                    <a href="{{ route('posts.show', $post->id) }}" class="list-group-item list-group-item-action">
                        <h5 class="mb-1">{{ $post->title }}</h5>
                        <p class="mb-1">{{ Str::limit($post->content, 100) }}</p>
                        <small>Posted on {{ $post->created_at->format('M d, Y') }}</small>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
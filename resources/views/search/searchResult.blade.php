<!-- resources/views/search/results.blade.php -->

@extends('layouts.header')

@section('content')
    <h1>Search Results</h1>

    <!-- Display Posts -->
    @if($posts->count())
        <h2>Posts</h2>
        <ul>
            @foreach($posts as $post)
                <li>{{ $post->title }}</li>
            @endforeach
        </ul>
    @endif

    <!-- Display Products -->
    @if($products->count())
        <h2>Products</h2>
        <ul>
            @foreach($products as $product)
                <li>{{ $product->name }}</li>
            @endforeach
        </ul>
    @endif
@endsection

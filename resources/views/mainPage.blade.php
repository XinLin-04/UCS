@extends('layouts.header')
@section('content')
@if (session('success'))
<div class="alert alert-success alert-success-homePage">
    {{ session('success') }}
</div>
@endif
<!-- Main Content - 80% Width -->
<div class="main-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-profile">
            @auth
            <div class="avatar" style="background-image: url('{{ asset('/images/tungtungtungsahur.jpg') }}');"></div>
            <div class="username" data-user-id="{{ Auth::id() }}" data-role="{{ Auth::user()->role }}">{{ Auth::user()->name }}</div>
            @else
            <div class="login-register">
                <a href="{{ route('login') }}" class="login-btn">Login</a>
                <a href="{{ route('register') }}" class="register-btn">Register</a>
            </div>
            @endauth
        </div>

        @can('create', App\Models\Complaint::class)
        <div class="new-post" id="open-complaint-form">
            <div class="icon">➕</div>
            <div class="text">New Post</div>
        </div>
        @auth
        <div class="my-posts">
            <div class="my-posts-title">My Post</div>
            <div class="post-list" id="user-posts">
            </div>
        </div>
        @endauth
        @endcan

        @auth
        <div class="logout">
            <div class="icon">📤</div>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="text">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
        @endauth
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <div class="content-header">
            <div class="section-title">Latest Discussion</div>
            <button class="filter-button" id="filter-toggle">⇅</button>

            <!-- Filter Dropdown -->
            <div class="filter-dropdown" id="filter-dropdown">
                <ul>
                    <li data-filter="recent" class="active">Latest Discussion</li>
                    <li data-filter="week">Top Discussion This Week</li>
                    <li data-filter="month">Top Discussion This Month</li>
                    <li data-filter="comments">Most Comments</li>
                </ul>
            </div>
        </div>

        <div class="posts-grid" id="all-posts">
            @foreach($complaints as $complaint)
            <a href="{{ route('complaints.show', $complaint->id) }}" class="discussion-post" data-id="{{ $complaint->id }}">
                <h3>{{ $complaint->title }}</h3>
                <p class="post-content">{{ Str::limit($complaint->content, 150) }}</p>
                <div class="post-meta">
                    <span class="post-author">By: {{ $complaint->user->name }}</span>
                    <span class="post-date">{{ $complaint->created_at->diffForHumans() }}</span>
                    <span class="comments-count">Comments: {{ $complaint->comments_count ?? 0 }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

    <!-- Complaint Form Modal -->
    <div id="complaint-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Create New Complaint</h2>

            <form id="complaint-form" method="POST" action="{{ route('complaints.store') }}">
                @csrf
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="6" required></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" id="cancel-complaint">Cancel</button>
                    <button type="submit">Submit Complaint</button>
                </div>
            </form>
        </div>
    </div>
@endsection
</body>

</html>

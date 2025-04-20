@extends('layouts.header')
@section('head')
<link rel="stylesheet" href="{{ asset('css/complaintDetail.css') }}">
@endsection

@section('content')
@section('content')
<!-- Main Content - 80% Width -->
<div class="main-container detail-container">
    <!-- Back to Main Page -->
    <div class="back-navigation">
        <a href="{{ route('complaints.index') }}" class="back-button">← Back to Discussions</a>
    </div>

    <!-- Complaint Detail Section -->
    <div class="complaint-detail">
        <h1 class="complaint-title">{{ $complaint->title }}</h1>

        <div class="complaint-meta">
            <div class="author-info">
                <div class="author-avatar"></div>
                <div class="author-name">{{ $complaint->user->name }}</div>
            </div>
            <div class="date-info">
                <span>Posted: {{ $complaint->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="complaint-content">
            {{ $complaint->content }}
        </div>

        @auth
        <div class="complaint-actions">
            @can('update', $complaint)
            <button class="edit-complaint-btn" data-id="{{ $complaint->id }}">Edit</button>
            @endcan

            @can('delete', $complaint)
            <button class="delete-complaint-btn" data-id="{{ $complaint->id }}">Delete</button>
            @endcan
        </div>
        @endauth

        <!-- Comments Section -->
        <div class="comments-section">
            <h2>Comments ({{ $comments->count() }})</h2>

            @auth
            <!-- Comment Form for Logged-in Users -->
            <div class="comment-form">
                <h3>Add Your Comment</h3>
                <form id="comment-form" method="POST" action="{{ route('comments.store') }}">
                    @csrf
                    <input type="hidden" name="complaint_id" value="{{ $complaint->id }}">
                    <div class="form-group">
                        <textarea name="content" id="comment-content" rows="4" placeholder="Write your comment here..."
                            required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit">Post Comment</button>
                    </div>
                </form>
            </div>
            @else
            <!-- Login prompt for non-logged in users -->
            <div class="login-to-comment">
                <p>Please <a href="{{ route('login') }}">login</a> to leave a comment</p>
            </div>
            @endauth

            <div class="comments-list">
                @if($comments->count() > 0)
                @foreach($comments as $comment)
                <div class="comment-item">
                    <div class="comment-meta">
                        <span class="comment-author">{{ $comment->user->name }}</span>
                        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="comment-text" id="comment-text-{{ $comment->id }}">
                        {{ $comment->content }}
                    </div>

                    @auth
                    <div class="comment-actions">
                        @can('update', $comment)
                        <button class="edit-comment-btn" data-id="{{ $comment->id }}">Edit</button>
                        @endcan

                        @can('delete', $comment)
                        <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-comment-btn">Delete</button>
                        </form>
                        @endcan
                    </div>
                    @endauth
                </div>
                @endforeach
                @else
                <div class="no-comments">No comments yet. Be the first to comment!</div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Here abit redundant-->
<!-- Edit Complaint Modal -->
<div id="edit-modal" class="modal">
    <div class="modal-content">
        <span class="close edit-close">&times;</span>
        <h2>Edit Complaint</h2>

        <form id="edit-form" method="POST" action="{{ route('complaints.update', $complaint) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit-title">Title</label>
                <input type="text" id="edit-title" name="title" value="{{ $complaint->title }}" required>
            </div>

            <div class="form-group">
                <label for="edit-content">Content</label>
                <textarea id="edit-content" name="content" rows="6" required>{{ $complaint->content }}</textarea>
            </div>

            <div class="form-actions">
                <button type="button" id="cancel-edit">Cancel</button>
                <button type="submit">Update Complaint</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="modal">
    <div class="modal-content">
        <span class="close delete-close">&times;</span>
        <h2>Delete Complaint</h2>

        <p>Are you sure you want to delete this complaint?</p>

        <form id="delete-form" method="POST" action="{{ route('complaints.destroy', $complaint) }}">
            @csrf
            @method('DELETE')

            <div class="form-actions">
                <button type="button" id="cancel-delete">Cancel</button>
                <button type="submit" class="delete-btn">Delete</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Comment Modal -->
<div id="edit-comment-modal" class="modal">
    <div class="modal-content">
        <span class="close edit-comment-close">&times;</span>
        <h2>Edit Comment</h2>

        <form id="edit-comment-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit-comment-content">Content</label>
                <textarea id="edit-comment-content" name="content" rows="4" required></textarea>
            </div>

            <div class="form-actions">
                <button type="button" id="cancel-edit-comment">Cancel</button>
                <button type="submit">Update Comment</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Comment Modal -->
<div id="delete-comment-modal" class="modal">
    <div class="modal-content">
        <span class="close delete-comment-close">&times;</span>
        <h2>Delete Comment</h2>

        <p>Are you sure you want to delete this comment?</p>

        <form id="delete-comment-form" method="POST" action="">
            @csrf
            @method('DELETE')

            <div class="form-actions">
                <button type="button" id="cancel-delete-comment">Cancel</button>
                <button type="submit" class="delete-btn">Delete</button>
            </div>
        </form>
    </div>
</div>

<!-- Flash Messages -->
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
@endsection
@section('scripts')
<script src="{{ asset('js/complaintDetail.js') }}"></script>
@endsection
</body>

</html>
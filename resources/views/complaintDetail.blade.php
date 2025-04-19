<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $complaint->title }} - UTAR Complainsion</title>
    <link rel="stylesheet" href="{{ asset('css/mainPage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/complaintDetail.css') }}">
</head>
<body>
    <!-- Header - Full Width -->
    <header class="header">
        <div class="logo-container">
            <div class="logo">u</div>
            <div class="site-title">UTAR Complainsion</div>
        </div>
        <div class="search-bar">
            <div class="search-icon">🔍</div>
            <input type="text" class="search-input">
        </div>
    </header>
    
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
                @if(Auth::id() == $complaint->user_id || Auth::user()->role == 'admin')
                <div class="complaint-actions">
                    <button class="edit-complaint-btn" data-id="{{ $complaint->id }}">Edit</button>
                    <button class="delete-complaint-btn" data-id="{{ $complaint->id }}">Delete</button>
                </div>
                @endif
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
                            <textarea name="content" id="comment-content" rows="4" placeholder="Write your comment here..." required></textarea>
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
                            <div class="comment-text">
                                {{ $comment->content }}
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="no-comments">No comments yet. Be the first to comment!</div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>

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
                
                @auth
                    @if(Auth::user()->role == 'admin')
                    <div class="form-group admin-note">
                        <label for="admin-note">Explanation Note (Required for Admin)</label>
                        <textarea id="admin-note" name="admin_note" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="user_role" value="admin">
                    @endif
                @endauth
                
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
                
                @auth
                    @if(Auth::user()->role == 'admin')
                    <div class="form-group admin-note">
                        <label for="delete-admin-note">Explanation Note (Required for Admin)</label>
                        <textarea id="delete-admin-note" name="admin_note" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="user_role" value="admin">
                    @endif
                @endauth
                
                <div class="form-actions">
                    <button type="button" id="cancel-delete">Cancel</button>
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

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/complaintDetail.js') }}"></script>
</body>
</html>
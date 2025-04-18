<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UTAR Complainsion</title>
    <link rel="stylesheet" href="{{ asset('css/mainPage.css') }}">
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
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="user-profile">
                @auth
                    <div class="avatar"></div>
                    <div class="username" data-user-id="{{ Auth::id() }}" data-role="{{ Auth::user()->role }}">{{ Auth::user()->name }}</div>
                @else
                    <div class="login-register">
                        <a href="{{ route('login') }}" class="login-btn">Login</a>
                        <a href="{{ route('register') }}" class="register-btn">Register</a>
                    </div>
                @endauth
            </div>
            
            @auth
                @if(Auth::user()->role != 'admin')
                <div class="new-post" id="open-complaint-form">
                    <div class="icon">➕</div>
                    <div class="text">New Post</div>
                </div>
                @endif
                
                <div class="my-posts">
                    <div class="my-posts-title">My Post</div>
                    <div class="post-list" id="user-posts">
                        <!-- User posts will be loaded here via JavaScript -->
                        <div class="loading-posts">Loading...</div>
                    </div>
                </div>
                
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
                <div class="section-title">Recent Top Discussion</div>
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
                <div class="discussion-post" data-id="{{ $complaint->id }}">
                    <h3>{{ $complaint->title }}</h3>
                    <p class="post-content">{{ Str::limit($complaint->content, 150) }}</p>
                    <div class="post-meta">
                        <span class="post-author">By: {{ $complaint->user->name }}</span>
                        <span class="post-date">{{ $complaint->created_at->diffForHumans() }}</span>
                        <span class="comments-count">Comments: {{ $complaint->comments_count ?? 0 }}</span>
                    </div>
                </div>
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

    <!-- Complaint Detail Modal -->
    <div id="detail-modal" class="modal">
        <div class="modal-content detail-content">
            <span class="close detail-close">&times;</span>
            <div id="complaint-detail-container">
                <h2 id="detail-title"></h2>
                
                <div class="detail-meta">
                    <span class="detail-author">By: <span id="detail-author-name"></span></span>
                    <span class="detail-date" id="detail-date"></span>
                </div>
                
                <div class="detail-content-text" id="detail-content"></div>
                
                @auth
                <div id="detail-actions" class="detail-actions">
                    <button id="detail-edit-btn" class="edit-post">Edit</button>
                    <button id="detail-delete-btn" class="delete-post">Delete</button>
                </div>
                @endauth
                
                <div class="comments-section">
                    <h3>Comments</h3>
                    <div id="comments-container"></div>
                    
                    @auth
                    <div class="comment-form">
                        <h4>Add a Comment</h4>
                        <form id="comment-form">
                            <input type="hidden" id="complaint-id" name="complaint_id">
                            <div class="form-group">
                                <textarea id="comment-content" name="content" rows="3" required></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit">Post Comment</button>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="login-to-comment">
                        <p>Please <a href="{{ route('login') }}">login</a> to comment</p>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Complaint Modal -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close edit-close">&times;</span>
            <h2>Edit Complaint</h2>
            
            <form id="edit-form" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit-title">Title</label>
                    <input type="text" id="edit-title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="edit-content">Content</label>
                    <textarea id="edit-content" name="content" rows="6" required></textarea>
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
            
            <form id="delete-form" method="POST" action="">
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

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/complaint.js') }}"></script>
</body>
</html>

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
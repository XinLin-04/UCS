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
                    <div class="username">{{ Auth::user()->name }}</div>
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
                <button class="filter-button">⇅</button>
            </div>
            
            <div class="posts-grid" id="all-posts">
                @foreach($complaints as $complaint)
                <div class="discussion-post" data-id="{{ $complaint->id }}">
                    <h3>{{ $complaint->title }}</h3>
                    <p class="post-content">{{ $complaint->content }}</p>
                    <div class="post-meta">
                        <span class="post-author">By: {{ $complaint->user->name }}</span>
                        <span class="post-date">{{ $complaint->created_at->diffForHumans() }}</span>
                    </div>
                    
                    @auth
                        @if(Auth::id() == $complaint->user_id || Auth::user()->role == 'admin')
                            <div class="post-actions">
                                <button class="edit-post" data-id="{{ $complaint->id }}">Edit</button>
                                <button class="delete-post" data-id="{{ $complaint->id }}">Delete</button>
                            </div>
                        @endif
                    @endauth
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const complaintModal = document.getElementById('complaint-modal');
            const editModal = document.getElementById('edit-modal');
            const deleteModal = document.getElementById('delete-modal');
            
            const openButton = document.getElementById('open-complaint-form');
            const closeButtons = document.querySelectorAll('.close');
            const cancelButtons = document.querySelectorAll('[id^="cancel-"]');
            
            // Open new complaint modal
            if (openButton) {
                openButton.addEventListener('click', function() {
                    complaintModal.style.display = 'block';
                });
            }
            
            // Close modals with X button
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    complaintModal.style.display = 'none';
                    editModal.style.display = 'none';
                    deleteModal.style.display = 'none';
                });
            });
            
            // Close modals with Cancel button
            cancelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    complaintModal.style.display = 'none';
                    editModal.style.display = 'none';
                    deleteModal.style.display = 'none';
                });
            });
            
            // Close when clicking outside the modal
            window.addEventListener('click', function(event) {
                if (event.target === complaintModal) {
                    complaintModal.style.display = 'none';
                }
                if (event.target === editModal) {
                    editModal.style.display = 'none';
                }
                if (event.target === deleteModal) {
                    deleteModal.style.display = 'none';
                }
            });
            
            // Edit buttons functionality
            const editButtons = document.querySelectorAll('.edit-post');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-id');
                    const postTitle = this.closest('.discussion-post').querySelector('h3').textContent;
                    const postContent = this.closest('.discussion-post').querySelector('.post-content').textContent;
                    
                    document.getElementById('edit-title').value = postTitle;
                    document.getElementById('edit-content').value = postContent;
                    document.getElementById('edit-form').action = `/complaints/${postId}`;
                    
                    editModal.style.display = 'block';
                });
            });
            
            // Delete buttons functionality
            const deleteButtons = document.querySelectorAll('.delete-post');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-id');
                    document.getElementById('delete-form').action = `/complaints/${postId}`;
                    deleteModal.style.display = 'block';
                });
            });
            
            // Load user posts via AJAX for authenticated users
            @auth
            const userPostsContainer = document.getElementById('user-posts');
            
            fetch('/api/user/complaints', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                userPostsContainer.innerHTML = '';
                
                if (data.length === 0) {
                    userPostsContainer.innerHTML = '<div class="no-posts">You have no posts yet.</div>';
                    return;
                }
                
                data.forEach(post => {
                    const postElement = document.createElement('div');
                    postElement.className = 'post-item';
                    postElement.innerHTML = `
                        <h4>${post.title}</h4>
                        <div class="post-date">${new Date(post.created_at).toLocaleDateString()}</div>
                    `;
                    userPostsContainer.appendChild(postElement);
                });
            })
            .catch(error => {
                console.error('Error fetching user posts:', error);
                userPostsContainer.innerHTML = '<div class="error-message">Failed to load posts.</div>';
            });
            @endauth
        });
    </script>
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
</body>
</html>
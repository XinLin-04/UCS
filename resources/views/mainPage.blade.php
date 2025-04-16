<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UTAR Complainsion</title>
    <link rel="stylesheet" href="{{ ('css/mainPage.css') }}">
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
                <div class="avatar">
                </div>
                <div class="username">Lee Wei Sen</div>
            </div>
            
            <div class="new-post" id="open-complaint-form">
                <div class="icon">➕</div>
                <div class="text">New Post</div>
            </div>
            
            <div class="my-posts">
                <div class="my-posts-title">My Post</div>
                <div class="post-list">
                    <div class="post-item"></div>
                    <div class="post-item"></div>
                    <div class="post-item"></div>
                    <div class="post-item"></div>
                </div>
            </div>
            
            <div class="logout">
                <div class="icon">📤</div>
                <div class="text">Logout</div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="content">
            <div class="content-header">
                <div class="section-title">Recent Top Discussion (Year, Month, Week)</div>
                <button class="filter-button">⇅</button>
            </div>
            
            <div class="posts-grid">
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
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

    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        // Modal functionality with vanilla JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('complaint-modal');
            const openButton = document.getElementById('open-complaint-form');
            const closeButton = document.querySelector('.close');
            const cancelButton = document.getElementById('cancel-complaint');
            
            // Open modal
            openButton.addEventListener('click', function() {
                modal.style.display = 'block';
            });
            
            // Close modal with X button
            closeButton.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            // Close modal with Cancel button
            cancelButton.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            // Close when clicking outside the modal
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
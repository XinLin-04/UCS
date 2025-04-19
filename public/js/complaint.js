document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Modal Elements
    const complaintModal = document.getElementById('complaint-modal');
    const detailModal = document.getElementById('detail-modal');
    
    // Open/Close Buttons
    const openButton = document.getElementById('open-complaint-form');
    const closeButtons = document.querySelectorAll('.close');
    const cancelButtons = document.querySelectorAll('[id^="cancel-"]');
    
    // Filter Dropdown
    const filterButton = document.getElementById('filter-toggle');
    const filterDropdown = document.getElementById('filter-dropdown');
    const filterOptions = document.querySelectorAll('.filter-dropdown li');
    
    // Initialize Active Filter
    let activeFilter = 'recent';
    
    // ===== MODAL HANDLING =====
    
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
            if (detailModal) detailModal.style.display = 'none';
        });
    });
    
    // Close modals with Cancel button
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            complaintModal.style.display = 'none';
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
            if (detailModal) detailModal.style.display = 'none';
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
        if (detailModal && event.target === detailModal) {
            detailModal.style.display = 'none';
        }
    });
    
    // ===== FILTER FUNCTIONALITY =====
    
    // Toggle filter dropdown
    if (filterButton) {
        filterButton.addEventListener('click', function() {
            filterDropdown.classList.toggle('active');
        });
    }
    
    // Handle filter selection
    if (filterOptions) {
        filterOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Update active class
                document.querySelector('.filter-dropdown li.active').classList.remove('active');
                this.classList.add('active');
                
                // Get filter value
                const filterValue = this.getAttribute('data-filter');
                activeFilter = filterValue;
                
                // Update UI based on selected filter
                updateFilterTitle(filterValue);
                
                // Close dropdown
                filterDropdown.classList.remove('active');
                
                // Fetch filtered posts
                fetchFilteredPosts(filterValue);
            });
        });
    }
    
    // Update section title based on selected filter
    function updateFilterTitle(filter) {
        const sectionTitle = document.querySelector('.section-title');
        if (!sectionTitle) return;
        
        switch(filter) {
            case 'recent':
                sectionTitle.textContent = 'Latest Discussion';
                break;
            case 'week':
                sectionTitle.textContent = 'Top Discussion This Week';
                break;
            case 'month':
                sectionTitle.textContent = 'Top Discussion This Month';
                break;
            case 'comments':
                sectionTitle.textContent = 'Most Comments';
                break;
            default:
                sectionTitle.textContent = 'Recent Top Discussion';
        }
    }
    
    // Fetch posts based on filter
    function fetchFilteredPosts(filter) {
        const postsContainer = document.getElementById('all-posts');
        if (!postsContainer) return;
        
        // Show loading state
        postsContainer.innerHTML = '<div class="loading-posts">Loading...</div>';
        
        // Fetch filtered posts via AJAX
        fetch(`/api/complaints?filter=${filter}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Clear container
            postsContainer.innerHTML = '';
            
            // Check if we have complaints
            if (data.length === 0) {
                postsContainer.innerHTML = '<div class="no-posts">No posts found.</div>';
                return;
            }
            
            // Build posts grid
            data.forEach(complaint => {
                const postElement = document.createElement('a');
                postElement.className = 'discussion-post';
                postElement.setAttribute('data-id', complaint.id);
                postElement.href = `/complaints/${complaint.id}`;
                
                // Truncate content for display
                const truncatedContent = complaint.content.length > 150 
                    ? complaint.content.substring(0, 150) + '...' 
                    : complaint.content;
                    
                postElement.innerHTML = `
                    <h3>${complaint.title}</h3>
                    <p class="post-content">${truncatedContent}</p>
                    <div class="post-meta">
                        <span class="post-author">By: ${complaint.user.name}</span>
                        <span class="post-date">${formatDate(complaint.created_at)}</span>
                        <span class="comments-count">Comments: ${complaint.comments_count || 0}</span>
                    </div>
                `;
                
                // Add action buttons for author or admin
                const currentUserId = getUserId();
                const isAdmin = getUserRole() === 'admin';
                
                if (currentUserId && (complaint.user_id === parseInt(currentUserId) || isAdmin)) {
                    postElement.appendChild(document.createElement('div')).className = 'post-actions';
                }
                
                postsContainer.appendChild(postElement);
            });
            
            attachEditDeleteEventListeners();
        })
        .catch(error => {
            console.error('Error fetching filtered posts:', error);
            postsContainer.innerHTML = '<div class="error-message">Failed to load posts.</div>';
        });
    }
    
    // ===== EDIT & DELETE FUNCTIONALITY =====
    
    // Attach event listeners to edit and delete buttons
    function attachEditDeleteEventListeners() {
        // Edit buttons
        const editButtons = document.querySelectorAll('.edit-post');
        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent navigation to detail page
                
                const postId = this.getAttribute('data-id');
                const postElement = this.closest('.discussion-post');
                const postTitle = postElement.querySelector('h3').textContent;
                const postContent = postElement.querySelector('.post-content').textContent.replace('...', '');
                
                setupEditModal(postId, postTitle, postContent);
            });
        });
        
        // Delete buttons
        const deleteButtons = document.querySelectorAll('.delete-post');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent navigation to detail page
                
                const postId = this.getAttribute('data-id');
                document.getElementById('delete-form').action = `/complaints/${postId}`;
                deleteModal.style.display = 'block';
            });
        });
    }
    
    // Setup the edit modal with post data
    function setupEditModal(postId, title, content) {
        document.getElementById('edit-title').value = title;
        document.getElementById('edit-content').value = content;
        document.getElementById('edit-form').action = `/complaints/${postId}`;
        
        editModal.style.display = 'block';
    }
    
    // ===== DETAIL VIEW FUNCTIONALITY =====
    
    // Open detail modal for a post
    function openDetailModal(complaintId) {
        if (!detailModal) return;
        
        fetch(`/api/complaints/${complaintId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update the modal content with complaint details
            document.getElementById('detail-title').textContent = data.title;
            document.getElementById('detail-content').textContent = data.content;
            document.getElementById('detail-author').textContent = data.user.name;
            document.getElementById('detail-date').textContent = formatDate(data.created_at);
            
            // Set the complaint ID for comment submission
            document.getElementById('complaint-id').value = data.id;
            
            // Clear comment form
            if (document.getElementById('comment-content')) {
                document.getElementById('comment-content').value = '';
            }
            
            // Load comments
            loadComments(data.id);
            
            // Display the modal
            detailModal.style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching complaint details:', error);
            alert('Failed to load complaint details.');
        });
    }
    
    // Add click handlers to discussion posts for detail view
    const discussionPosts = document.querySelectorAll('.discussion-post');
    discussionPosts.forEach(post => {
        post.addEventListener('click', function(e) {
            // Only proceed if the click was not on an action button
            if (!e.target.closest('.post-actions')) {
                const postId = this.getAttribute('data-id');
                openDetailModal(postId);
                e.preventDefault(); // Prevent default link behavior
            }
        });
    });
    
    // ===== HELPER FUNCTIONS =====
    
    // Date formatter helper
    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) {
            return 'Today';
        } else if (diffDays === 1) {
            return 'Yesterday';
        } else if (diffDays < 7) {
            return `${diffDays} days ago`;
        } else {
            return date.toLocaleDateString();
        }
    }
    
    // Get current user ID
    function getUserId() {
        const userElement = document.querySelector('.username');
        if (!userElement) return null;
        return userElement.getAttribute('data-user-id');
    }
    
    // Get current user role
    function getUserRole() {
        const userElement = document.querySelector('.username');
        if (!userElement) return null;
        return userElement.getAttribute('data-role');
    }
    
    // Load user posts via AJAX for authenticated users
    function fetchUserComplaints() {
        const userPostsContainer = document.getElementById('user-posts');
        if (!userPostsContainer) return;
        fetch('/api/user/complaints', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Clear existing content
            userPostsContainer.innerHTML = '';

            // Create container for boxes
            const boxesContainer = document.createElement('div');
            boxesContainer.className = 'complaints-grid';

            // Add complaint boxes
            data.complaints.forEach(complaint => {
                const box = document.createElement('div');
                box.className = 'complaint-box';
                box.setAttribute('data-id', complaint.id);
                box.style.cursor = 'pointer';
                box.innerHTML = `
                    <h3>${complaint.title}</h3>
                    <p>${complaint.content.substring(0, 100)}...</p>
                    <div class="complaint-date">${formatDate(complaint.created_at)}</div>
                `;

                // Add click event to navigate to complaint view
                box.addEventListener('click', () => {
                    window.location.href = `/complaints/${complaint.id}`;
                });

                boxesContainer.appendChild(box);
            });

            userPostsContainer.appendChild(boxesContainer);
        })
        .catch(error => {
            console.error('Fetch error:', error);
            userPostsContainer.innerHTML = '<p>Error loading complaints</p>';
        });
    }
    
    // Flash Messages Auto-Hide
    const flashMessages = document.querySelectorAll('.alert');
    if (flashMessages.length > 0) {
        flashMessages.forEach(message => {
            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => {
                    message.style.display = 'none';
                }, 500);
            }, 5000);
        });
    }
    
    // Click outside filter dropdown to close it
    document.addEventListener('click', function(e) {
        if (filterDropdown && filterDropdown.classList.contains('active')) {
            if (!filterDropdown.contains(e.target) && e.target !== filterButton) {
                filterDropdown.classList.remove('active');
            }
        }
    });
    
    // Initialize the page
    attachEditDeleteEventListeners();
    fetchUserComplaints();
    
    // By default, apply the "recent" filter
    if (filterButton) {
        // Automatically apply the default filter on page load
        fetchFilteredPosts('recent');
    }
});
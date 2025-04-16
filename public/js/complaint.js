// Place this file in your public/js directory or as part of your app.js build

document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modal = document.getElementById('complaint-modal');
    const openButton = document.getElementById('open-complaint-form');
    const closeButton = document.querySelector('.close');
    const cancelButton = document.getElementById('cancel-complaint');
    
    // Open modal when clicking on new post
    if (openButton) {
        openButton.addEventListener('click', function() {
            modal.style.display = 'block';
        });
    }
    
    // Close modal with X button
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    
    // Close modal with Cancel button
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    
    // Close when clicking outside the modal
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Form submission
    const complaintForm = document.getElementById('complaint-form');
    if (complaintForm) {
        complaintForm.addEventListener('submit', function(e) {
            // You can add form validation here if needed
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            
            if (!title || !content) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    }

    // Fetch user's complaints
    fetchUserComplaints();
});

// Function to fetch user's complaints
function fetchUserComplaints() {
    const postList = document.querySelector('.post-list');
    if (!postList) return;

    // Fetch the user's complaints using AJAX
    fetch('/api/user/complaints', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Clear the current post list
        postList.innerHTML = '';
        
        // Check if we have complaints
        if (data.length === 0) {
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'post-item';
            emptyMessage.textContent = 'No complaints yet';
            postList.appendChild(emptyMessage);
            return;
        }
        
        // Add each complaint to the list
        data.forEach(complaint => {
            const postItem = document.createElement('div');
            postItem.className = 'post-item';
            
            const title = document.createElement('div');
            title.className = 'post-title';
            title.textContent = complaint.title;
            
            const date = document.createElement('div');
            date.className = 'post-date';
            date.textContent = new Date(complaint.created_at).toLocaleDateString();
            
            postItem.appendChild(title);
            postItem.appendChild(date);
            postList.appendChild(postItem);
        });
    })
    .catch(error => {
        console.error('Error fetching complaints:', error);
    });
}
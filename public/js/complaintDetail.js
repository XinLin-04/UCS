document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Modal Elements
    const editModal = document.getElementById('edit-modal');
    const deleteModal = document.getElementById('delete-modal');
    
    // Button Elements
    const editBtn = document.querySelector('.edit-complaint-btn');
    const deleteBtn = document.querySelector('.delete-complaint-btn');
    const closeButtons = document.querySelectorAll('.close');
    const cancelButtons = document.querySelectorAll('[id^="cancel-"]');
    
    // Setup Edit Button
    if (editBtn) {
        editBtn.addEventListener('click', function() {
            editModal.style.display = 'block';
        });
    }
    
    // Setup Delete Button
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            deleteModal.style.display = 'block';
        });
    }
    
    // Close modals with X button
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
        });
    });
    
    // Close modals with Cancel button
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
        });
    });
    
    // Close when clicking outside the modal
    window.addEventListener('click', function(event) {
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
        if (event.target === deleteModal) {
            deleteModal.style.display = 'none';
        }
    });
    
    // Comment Form Submission
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            // Form will be submitted normally, using the standard form action
            // You could convert this to AJAX if you prefer
            
            // Validate comment content
            const content = document.getElementById('comment-content').value.trim();
            if (!content) {
                e.preventDefault();
                alert('Please enter a comment.');
            }
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
});
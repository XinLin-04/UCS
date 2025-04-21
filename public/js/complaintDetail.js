document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Complaint Modals
    const editModal = document.getElementById('edit-modal');
    const deleteModal = document.getElementById('delete-modal');

    // Comment Modals
    const editCommentModal = document.getElementById('edit-comment-modal');
    const deleteCommentModal = document.getElementById('delete-comment-modal');
    const editCommentForm = document.getElementById('edit-comment-form');
    const deleteCommentForm = document.getElementById('delete-comment-form');
    const commentTextarea = document.getElementById('edit-comment-content');

    // Buttons
    const editBtn = document.querySelector('.edit-complaint-btn');
    const deleteBtn = document.querySelector('.delete-complaint-btn');
    const closeButtons = document.querySelectorAll('.close');
    const cancelButtons = document.querySelectorAll('[id^="cancel-"]');

    // Setup Complaint Edit
    if (editBtn) {
        editBtn.addEventListener('click', function() {
            editModal.style.display = 'block';
        });
    }

    // Setup Complaint Delete
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            deleteModal.style.display = 'block';
        });
    }

    // Setup Comment Edit
    const editCommentBtns = document.querySelectorAll('.edit-comment-btn');
    editCommentBtns.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = button.getAttribute('data-id');
            const commentText = document.getElementById(`comment-text-${commentId}`).innerText.trim();

            // Set action URL
            editCommentForm.action = `/comments/${commentId}`;
            commentTextarea.value = commentText;
            editCommentModal.style.display = 'block';
        });
    });

    // Setup Comment Delete
    const deleteCommentBtns = document.querySelectorAll('.delete-comment-btn');
    deleteCommentBtns.forEach(button => {
        button.addEventListener('click', function(e) {
            // prevent the form from submitting immediately
            e.preventDefault();
            const form = button.closest('form');
            const action = form.getAttribute('action');

            deleteCommentForm.action = action;
            deleteCommentModal.style.display = 'block';
        });
    });

    // Close Modals with X
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
            editCommentModal.style.display = 'none';
            deleteCommentModal.style.display = 'none';
        });
    });

    // Close Modals with Cancel
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            editModal.style.display = 'none';
            deleteModal.style.display = 'none';
            editCommentModal.style.display = 'none';
            deleteCommentModal.style.display = 'none';
        });
    });

    // Close modals by clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === editModal) editModal.style.display = 'none';
        if (event.target === deleteModal) deleteModal.style.display = 'none';
        if (event.target === editCommentModal) editCommentModal.style.display = 'none';
        if (event.target === deleteCommentModal) deleteCommentModal.style.display = 'none';
    });

    // Comment Form Validation
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            const content = document.getElementById('comment-content').value.trim();
            if (!content) {
                e.preventDefault();
                alert('Please enter a comment.');
            }
        });
    }

    // Flash Message Auto-Hide
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

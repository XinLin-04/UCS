document.getElementById('avatar-input').addEventListener('change', function (event) {
    const file = event.target.files[0]; // Get the selected file
    const maxSize = 3 * 1024 * 1024; // 3 MB in bytes

    if (file && file.size > maxSize) {
        // Show an alert or error message
        alert('The profile picture must not exceed 3 MB.');
        event.target.value = ''; // Clear the file input
    }
});
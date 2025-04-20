setTimeout(() => {
    const statusMessage = document.getElementsByClassName('alert')[0];
    if (statusMessage) {
        statusMessage.style.display = 'none';
    }
}, 5000); // 5000ms = 5 seconds
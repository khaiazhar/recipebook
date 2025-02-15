<head>
<style>
    body {
        background: url('wallpaper.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }
</style>
</head>



document.getElementById('logoutBtn').addEventListener('click', function() {
    fetch('logout.php', {
        method: 'POST', // Method could also be GET depending on your setup
        credentials: 'same-origin' // Include cookies in the request
    })
    .then(response => {
        if (response.ok) {
            // Redirect to login page or perform any other action upon successful logout
            window.location.href = 'login.php'; // Redirect to login page
        } else {
            console.error('Logout failed'); // Handle error if logout fails
        }
    })
    .catch(error => console.error('Error during logout:', error));
});

<?php
session_start();

session_destroy();

header('Location:/Recipebook/index.php');
exit;
?>


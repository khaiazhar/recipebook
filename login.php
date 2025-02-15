<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$connect = mysqli_connect("localhost", "root", "", "Recipebook");

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Get selected role

    // Restrict Admin Login to Only admin@gmail.com
    if ($role == "admin" && $email !== "admin@gmail.com") {
        echo "<script>alert('Only admin@gmail.com can log in as an admin!'); window.location.href='login.php';</script>";
        exit();
    }

    // Validate credentials
    $query = "SELECT * FROM user WHERE email = ? AND role = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $role);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            // Redirect based on role
            if ($role == "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid email or role. Please check your credentials.');</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
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
<body>
    <div class="login">
        <img src="uploads/logo1.jpg" alt="login image" class="login__img">

        <form action="login.php" method="POST" class="container">
            <h1 class="login__title">Login</h1>

            <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

            <div class="login__content">
                <div class="login__box">
                    <i class="ri-user-3-line login__icon"></i>
                    <div class="login__box-input">
                        <input type="email" name="email" required class="login__input" id="login-email" placeholder=" ">
                        <label for="login-email" class="login__label">Email</label>
                    </div>
                </div>

                <div class="login__box">
                    <i class="ri-lock-2-line login__icon"></i>
                    <div class="login__box-input">
                        <input type="password" name="password" required class="login__input" id="login-pass" placeholder=" ">
                        <label for="login-pass" class="login__label">Password</label>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="login__box">
                    <i class="ri-user-settings-line login__icon"></i>
                    <label for="role" class="login__label">Role:</label>
                    <select name="role" required class="login__input">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div class="login__check">
                <div class="login__check-group">
                    <input type="checkbox" class="login__check-input" id="login-check" name="remember">
                    <label for="login-check" class="login__check-label">Remember me</label>
                </div>
            </div>

            <button type="submit" class="login__button">Login</button>

            <p class="login__register">
                Don't have an account? <a href="register.php">Register</a>
            </p>

            <!-- Back to Home Button -->
            <button class="back-button" onclick="window.location.href='index.php'">Back to Home</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Recipebook. All Rights Reserved.</p>
    </footer>
</body>
</html>


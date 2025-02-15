<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "Recipebook");

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = $_POST['password']; // Get raw password before hashing
    $role = mysqli_real_escape_string($connect, $_POST['role']);

    // Admin account restriction
    if ($role == "admin") {
        // Check if an admin already exists
        $admin_check = "SELECT * FROM user WHERE role='admin'";
        $admin_result = mysqli_query($connect, $admin_check);

        if (mysqli_num_rows($admin_result) > 0) {
            echo "<script>alert('Admin account already exists!'); window.location.href='register.php';</script>";
            exit();
        }

        // Ensure the admin email and password are fixed
        if ($email !== "admin@gmail.com" || $password !== "Admin123") {
            echo "<script>alert('Admin must use email: admin@gmail.com and password: Admin123'); window.location.href='register.php';</script>";
            exit();
        }
    }

    // Hash password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($role)) {
        $query = "INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $role);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Registration successful! Redirecting to login...'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Registration failed. Try again!');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Please fill out all fields.');</script>";
    }
}

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            background: url('wallpaper.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 90%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: blue;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: darkblue;
        }

        .back-button {
            background: gray;
        }
        .back-button:hover {
            background: darkgray;
        }
        .footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="role">Select Role</label>
                <select name="role" id="role" required>
                    <option value="">Please select</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit">Register</button>
            <p class="login__register">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </form>
        <button class="back-button" onclick="window.location.href='index.php'">Back to Home</button>
    </div>
    
    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Recipebook. All Rights Reserved.</p>
    </footer>
</body>
</html>

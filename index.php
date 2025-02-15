<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Book - Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: arial, sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .navbar {
            display: flex;
            justify-content: flex-end;
            background: rgba(0, 0, 0, 0.8);
            padding: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .navbar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 0px;
            position: fixed;
            width: 100%;
            bottom: 0;
            font-size: 14px;
        }
        h1 {
            font-family: 'Cinzel', serif; 
            color: black;
            font-size: 36px; 
            font-weight: bold;
            text-transform: uppercase; 
            text-align: center;
            letter-spacing: 2px; 
            margin-bottom: 20px;
        }
        h2 {
            font-family: 'Playfair Display', serif; 
            color: maroon;
            font-size: 20px; 
            font-weight: bold;
            text-align: center;
        }
        h3 {
            font-family: 'Cormorant Garamond', serif; 
            color: black;
            font-size: 18px; 
            line-height: 1.6; 
            text-align: center;
        }
/* Ensure Playfair Display font is loaded */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap');


    </style>
</head>
<body>

    <!-- Small Navigation Bar (Top-Right) -->
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>

    <!-- Home Page Content -->
    <div class="container">
        <h1>Welcome to Recipe Book</h1>
        <h3>Create, share, and discover delicious recipes! Upload your own recipes with images, edit them anytime, and share your dishes with photos on our simple recipe platform.🍽️✨</h3>

       <br><img src="uploads/logo.jpg" alt="login image" class="login__img"><br><br>
        <br><h2>Share your culinary passion with the world! Log in to add your recipes, edit existing ones, and curate a personalized recipe collection for everyone to enjoy.</h2>
        <br><a href="login.php" class="btn btn-primary">Get Started</a>
    </div>
    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Recipebook. All Rights Reserved.</p>
    </footer>
</body>
</html>

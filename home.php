<?php
session_start();

// Ensure user is logged in
if (empty($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Connect to database
$connect = mysqli_connect("localhost", "root", "", "Recipebook");

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle search query
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connect, $_GET['search']);
    $query = "SELECT * FROM recipe WHERE recipe_name LIKE '%$search%' ORDER BY recipe_name DESC";
} else {
    $query = "SELECT * FROM recipe ORDER BY recipe_name DESC";
}

$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Book - User Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: url('back.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        td {
        white-space: pre-wrap; /* Ensures text wraps properly */
        word-wrap: break-word; /* Breaks long words if needed */
        max-width: 250px; /* Adjust width for better readability */
        }
        .custom-search {
            background: linear-gradient(45deg, #333, #222);
            color: beige;
            border-radius: 25px;
            border: none;
            padding: 10px 20px;
            transition: all 0.3s ease-in-out;
        }
        .custom-search:hover {
            background: linear-gradient(45deg, #222, #555);
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center">Welcome to Recipe Book</h2>
        
        <div class="d-flex justify-content-between my-3">
            <a class="btn btn-primary" href="add_item.php">Add Recipe</a>
            <a class="btn btn-secondary" href="log_out.php">Logout</a>
        </div>

        <!-- Search Bar -->
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search recipe name..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn custom-search">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Recipe Name</th>
                        <th>Ingredients</th>
                        <th>Instructions</th>
                        <th>Cooking Method</th>
                        <th>Cooking Display</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>
                            <td>' . $counter++ . '</td>
                            <td>' . (!empty($row["Recipe_Name"]) ? htmlspecialchars($row["Recipe_Name"]) : "N/A") . '</td>
                            <td>' . (!empty($row["Ingredients"]) ? htmlspecialchars($row["Ingredients"]) : "N/A") . '</td>
                            <td>' . (!empty($row["Instructions"]) ? htmlspecialchars($row["Instructions"]) : "N/A") . '</td>
                            <td>' . (!empty($row["Cooking_Method"]) ? htmlspecialchars($row["Cooking_Method"]) : "N/A") . '</td>
                            <td>';
                            
                    if (!empty($row["Cooking_Display"])) {
                        echo '<img src="' . htmlspecialchars($row["Cooking_Display"]) . '" alt="Cooking Image" width="100">'; 
                        echo '<a class="btn btn-outline-success btn-sm mt-1" href="' . htmlspecialchars($row["Cooking_Display"]) . '" download>Download</a>';
                    } else {
                        echo "N/A";
                    }

                    echo '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($connect);
?>

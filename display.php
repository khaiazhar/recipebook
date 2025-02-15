<?php
session_start();

// Pastikan pengguna telah log masuk
if (empty($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Sambungkan ke database
$connect = mysqli_connect("localhost", "root", "", "Recipebook");

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Proses penghapusan jika ada parameter 'del'
if (isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $delete = "DELETE FROM recipe WHERE id=?";
    $stmt = mysqli_prepare($connect, $delete);
    mysqli_stmt_bind_param($stmt, "i", $del_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.open('display.php', '_self');</script>";
    } else {
        echo "Failed to delete, try again.";
    }
    mysqli_stmt_close($stmt);
}

// Dapatkan data dari jadual `recipe`
$query = "SELECT * FROM recipe ORDER BY recipe_name DESC";
$result = mysqli_query($connect, $query);
?>
<style>
     p {
            font-family: 'Cormorant Garamond', serif; 
            color: darkblue;
            font-size: 18px; 
            line-height: 1.6; 
            text-align: center;
        }
        
    td {
        white-space: pre-wrap; /* Ensures text wraps properly */
        word-wrap: break-word; /* Breaks long words if needed */
        max-width: 250px; /* Adjust width for better readability */
        }
        
</style>
<!DOCTYPE html>
<html>
<head>
    <title>List of Recipe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <style>
        body{
            background: url('back.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
    </style> 

    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure to delete this recipe?")) {
                window.location.href = 'display.php?del=' + id;
            }
        }
        
        function searchRecipe() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#view_data tbody tr");
            
            rows.forEach(row => {
                let recipeName = row.cells[1].textContent.toLowerCase();
                row.style.display = recipeName.includes(input) ? "" : "none";
            });
        }
    </script>

</head>
<body>
    <div class="container my-5">
        <h2 class="text-center">List of Recipes</h2>
        <p>Share your favorite recipe !</p>
        <div class="d-flex justify-content-between my-3">
            <a class="btn btn-primary" href="add_item.php">Add Recipe</a>
            <a class="btn btn-dark" href="home.php">Dashboard</a> 
            <a class="btn btn-secondary" href="log_out.php">Logout</a>
        </div>
        
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by Recipe Name..." onkeyup="searchRecipe()">
        </div>

        <div class="table-responsive">
            <table id="view_data" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Recipe Name</th>
                        <th>Ingredients</th>
                        <th>Instructions</th>
                        <th>Cooking Method</th>
                        <th>Cooking Display</th>
                        <th>Actions</th>
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
                        echo '<a href="' . htmlspecialchars($row["Cooking_Display"]) . '" target="_blank" class="btn btn-outline-primary btn-sm">View</a>';
                    } else {
                        echo "N/A";
                    }
    
                        echo '</td>
                            <td>
                                <a class="btn btn-outline-primary btn-sm" href="edit_item.php?id=' . htmlspecialchars($row["Id"]) . '">Edit</a>  
                            </td>
                        </tr>';
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


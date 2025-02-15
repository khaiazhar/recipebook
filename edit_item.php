<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$connect = mysqli_connect("localhost", "root", "", "Recipebook");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$errorMessage = $successMessage = "";
$Recipe_Name = $Ingredients = $Instructions = $Cooking_Method = $Cooking_Display = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Fetch record from database
    $query = "SELECT * FROM recipe WHERE id=?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $Recipe_Name = isset($row['Recipe_Name']) ? $row['Recipe_Name'] : "";
        $Ingredients = isset($row['Ingredients']) ? $row['Ingredients'] : "";
        $Instructions = isset($row['Instructions']) ? $row['Instructions'] : "";
        $Cooking_Method = isset($row['Cooking_Method']) ? $row['Cooking_Method'] : "";
        $Cooking_Display = isset($row['Cooking_Display']) ? $row['Cooking_Display'] : "";
    } else {
        $errorMessage = "No record found with ID: " . htmlspecialchars($id);
    }
    mysqli_stmt_close($stmt);
} else {
    $errorMessage = "No ID specified.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $Recipe_Name = $_POST['Recipe_Name'];
    $Ingredients = $_POST['Ingredients'];
    $Instructions = $_POST['Instructions'];
    $Cooking_Method = $_POST['Cooking_Method'];

    // Handle file upload
    if (isset($_FILES['Cooking_Display']) && $_FILES['Cooking_Display']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["Cooking_Display"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["Cooking_Display"]["tmp_name"], $target_file)) {
                $Cooking_Display = $target_file;
            } else {
                $errorMessage = "Error uploading file.";
            }
        } else {
            $errorMessage = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        $Cooking_Display = $_POST['existing_cover'];
    }

    // Update database
    if (empty($errorMessage) && $id > 0) {
        $update_query = "UPDATE recipe SET recipe_name=?, ingredients=?, instructions=?, cooking_method=?, cooking_display=? WHERE id=?";
        $stmt = mysqli_prepare($connect, $update_query);
        mysqli_stmt_bind_param($stmt, "sssssi", $Recipe_Name, $Ingredients, $Instructions, $Cooking_Method, $Cooking_Display, $id);

        if (mysqli_stmt_execute($stmt)) {
            $successMessage = "Record updated successfully!";
            header("Location: display.php");
            exit();
        } else {
            $errorMessage = "Error updating record: " . mysqli_error($connect);
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($connect);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body{
            background: url('back2.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
</style> 
</head>
<body>
    <div class="container my-5">
        <h2>Edit Recipe</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong><?php echo $errorMessage; ?></strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong><?php echo $successMessage; ?></strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="existing_cover" value="<?php echo htmlspecialchars($Cooking_Display); ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Recipe Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Recipe_Name"  value="<?php echo htmlspecialchars($Recipe_Name); ?>">
                </div>
            </div>
            <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Ingredients</label>
                    <div class="col-sm-6">
                    <textarea class="form-control" name="Ingredients" rows="4"><?php echo htmlspecialchars($Ingredients); ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Instructions</label>
                    <div class="col-sm-6">
                    <textarea class="form-control" name="Instructions" rows="6"><?php echo htmlspecialchars($Instructions); ?></textarea>
                </div>
            </div>

            <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="existing_cover" value="<?php echo htmlspecialchars($Cooking_Display); ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Cooking Method</label>
                <div class="col-sm-6">
                    <select class="form-control" name="Cooking_Method">
                        <option value="">Please Select</option>
                        <option value="Boiling" <?php if ($Cooking_Method == "Boiling") echo "selected"; ?>>Boiling</option>
                        <option value="Frying" <?php if ($Cooking_Method == "Frying") echo "selected"; ?>>Frying</option>
                        <option value="Grilling" <?php if ($Cooking_Method == "Grilling") echo "selected"; ?>>Grilling</option>
                        <option value="Steaming" <?php if ($Cooking_Method == "Steaming") echo "selected"; ?>>Steaming</option>
                        <option value="Baking" <?php if ($Cooking_Method == "Baking") echo "selected"; ?>>Baking</option>
                        <option value="Roasting" <?php if ($Cooking_Method == "Roasting") echo "selected"; ?>>Roasting</option>
                        <option value="Stewing" <?php if ($Cooking_Method == "Stewing") echo "selected"; ?>>Stewing</option>
                        <option value="Sautéing" <?php if ($Cooking_Method == "Sautéing") echo "selected"; ?>>Sautéing</option>
                        <option value="Poaching" <?php if ($Cooking_Method == "Poaching") echo "selected"; ?>>Poaching</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Cooking Display</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="Cooking_Display">
                    <img src="<?php echo htmlspecialchars($Cooking_Display); ?>" alt="Current Display" class="img-thumbnail mt-3" width="150">
                </div>
            </div>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="display.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

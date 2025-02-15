<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "Recipebook";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$Recipe_Name = "";
$Ingredients = "";
$Instructions = "";
$Cooking_Method = "";
$Cooking_Display = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Recipe_Name = isset($_POST["Recipe_Name"]) ? trim($_POST["Recipe_Name"]) : "";
    $Ingredients = isset($_POST["Ingredients"]) ? trim($_POST["Ingredients"]) : "";
    $Instructions = isset($_POST["Instructions"]) ? trim($_POST["Instructions"]) : "";
    $Cooking_Method = isset($_POST["Cooking_Method"]) ? trim($_POST["Cooking_Method"]) : "";
    $Cooking_Display = "";

    // Validate input fields
    if (empty($Recipe_Name) || empty($Ingredients) || empty($Instructions) || empty($Cooking_Method) || empty($_FILES["Cooking_Display"]["name"])) {
        $errorMessage = "All fields are required";
    } else {
        // Handle file upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["Cooking_Display"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $allowed_extensions)) {
            if (move_uploaded_file($_FILES["Cooking_Display"]["tmp_name"], $target_file)) {
                $Cooking_Display = $target_file;
                $sql = "INSERT INTO recipe (recipe_name, instructions, ingredients, cooking_method, cooking_display) VALUES (?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("sssss", $Recipe_Name, $Ingredients, $Instructions, $Cooking_Method, $Cooking_Display);

                if ($stmt->execute()) {
                    $successMessage = "Recipe added successfully.";
                    header("Location: display.php");
                    exit();
                } else {
                    $errorMessage = "Error: " . $stmt->error;
                }
            } else {
                $errorMessage = "Sorry, there was an error uploading your file.";
            }
        } else {
            $errorMessage = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body{
            background: url('back1.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
</style> 
</head>
<body>
    <div class="container my-5">
        <h2>Add Cooking Recipe</h2>

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
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Recipe Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="Recipe_Name" value="<?php echo htmlspecialchars($Recipe_Name); ?>">
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
                </div>
            </div>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="display.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

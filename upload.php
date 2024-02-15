<?php
session_start();

// Check if admin is not logged in, redirect to login page
if(!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit;
}

$upload_dir = "uploads/";
$allowed_types = array('jpg', 'jpeg', 'png', 'php');

// Initialize variables
$name = $image_path = '';
$name_err = $image_err = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter the car name.";
    } else {
        $name = trim($_POST["name"]);
    }

    $file_name = basename($_FILES["image"]["name"]);
    $target_path = $upload_dir . $file_name;
    $image_file_type = pathinfo($target_path, PATHINFO_EXTENSION);

    if (!in_array(strtolower($image_file_type), $allowed_types)) {
        $image_err = "Only JPG, JPEG, and PNG files are allowed.";
    } else if (file_exists($target_path)) {
        $image_err = "File already exists.";
    }

    if (empty($name_err) && empty($image_err)) {
        include 'db_connection.php';
        $sqlite_conn = new SqliteConnection();
        $pdo = $sqlite_conn->connect();

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
            $prepare_statement = $pdo->prepare("INSERT INTO cars (name, image_path) VALUES (:name, :image_path)");
            $result =  $prepare_statement->execute([
                ":name" => $name,
                ":image_path" => $target_path
                ]
            );
            if ($result) $image_err = "Upload with success";
        } else $image_err = "Error uploading file.";
        
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Car</title>
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5 mb-4">Upload Car</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="carName">Car Name:</label>
                <input type="text" id="carName" class="form-control" name="name" value="<?php echo $name; ?>">
                <span class="text-danger"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="carImage">Car Image:</label>
                <input type="file" id="carImage" class="form-control-file" name="image">
                <span class="text-danger"><?php echo $image_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Upload">
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
session_start();
/* 
if(isset($_SESSION['admin_username'])) {
    header("Location: upload.php");
    exit;
} */

if($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';

    $sqlite_conn = new SqliteConnection();
    $pdo = $sqlite_conn->connect();

    $username = $_POST['username'];
    $password = $_POST['password'];

    $prepare_statement = $pdo->prepare("SELECT password FROM admins WHERE username = :username");
    $prepare_statement->execute([":username"=> $username]);

    $login_err = ""; // Initialize login error variable

    // Check if username exists
    if ($row = $prepare_statement->fetch(PDO::FETCH_ASSOC)) {
        // Verify password
        if ($row["password"] == $password) {
            $_SESSION['admin_username'] = $username;
            header("Location: upload.php");
            exit; // Ensure no further code execution after redirection
        } else {
            $login_err = "Invalid password.";
        }
    } else {
        $login_err = "Invalid username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .card {
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-center mb-4">Admin Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
        </form>
        <?php
        if(isset($login_err)) {
            echo '<p class="text-danger">' . $login_err . '</p>';
        }
        ?>
    </div>
</body>
</html>

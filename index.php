<?php
    include 'db_connection.php';
    $sqlite_conn = new SqliteConnection();
    $pdo = $sqlite_conn->connect();
    $prepare_statement = $pdo->query("SELECT name, image_path FROM cars");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Catalogue</title>
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <style>
        .car-container {
            margin-top: 20px;
        }
        .car {
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .car-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
            text-align: center;
        }
        .car-img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4 text-center">Car Catalogue</h1>
        <div class="row car-container">
            <?php while ($row = $prepare_statement->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="col-md-4">
                    <div class="car">
                        <img src="<?php echo $row["image_path"]; ?>" class="img-fluid car-img" alt="Car Image">
                        <h2 class="car-name"><?php echo $row["name"]; ?></h2>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>

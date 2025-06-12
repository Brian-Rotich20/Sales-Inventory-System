<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];


    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    
    header("Location: ../auth/dashboard.php");
    exit();
}

$id = $_GET['id']; // Show confirmation form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Deletion</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .confirm-box {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: #fff3f3;
            border: 1px solid #ff4c4c;
            border-radius: 8px;
            text-align: center;
        }

        .btn {
            padding: 8px 16px;
            margin: 10px 5px;
            text-decoration: none;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn.confirm {
            background-color:rgb(223, 29, 15); /* red */
        }

        .btn.cancel {
            background-color: #555; /* gray */
        }
    </style>
</head>
<body>

<div class="confirm-box">
    <h2>Are you sure you want to delete this product?</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <button type="submit" class="btn confirm">Yes, Delete</button>
        <a href="../products/AllProducts.php" class="btn cancel">Cancel</a>
    </form>
</div>

</body>
</html>

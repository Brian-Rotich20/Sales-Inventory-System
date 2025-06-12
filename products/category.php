<?php
include '../config/db.php';

$message = '';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);

    
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            $message = "Category added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Category name cannot be empty.";
    }
       
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/products.css">
     <link rel="stylesheet" href="../css/sales.css">
     <div class="products-sideabar">
             <?php include '../includes/sidebar.php'; ?>
        </div>
    <style>
        body {
            font-family: Arial;
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        input[type="text"] {
            padding: 0.5rem;
            width: 100%;
            max-width: 400px;
        }
        .btn {
            padding: 0.5rem 1rem;
            background: #28a745;
            border: none;
            color: #fff;
            cursor: pointer;
        }
        .message {
            margin-top: 1rem;
            color: green;
        }
    </style>
</head>
<body>
<div class="main-content">
    <h2>Add New Category</h2>

    <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

    <form action="category.php" method="POST">
        <div class="form-group">
            <label for="category_name">Category Name</label><br>
            <input type="text" name="category_name" id="category_name" required>
        </div>
        <button type="submit" class="btn">Add Category</button>
    </form>

</div>

</body>
</html>

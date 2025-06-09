<?php
include '../config/db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizing and validating user input
    $name = htmlspecialchars(trim($_POST['name']));
    $qty = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

        // If correct then insert
    if($name && $qty !==false && $price !==false){
        $stmt = $conn->prepare("INSERT INTO products (name, quantity, price) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $name, $qty, $price); // s= string
        $stmt->execute();

         header("Location: ../auth/dashboard.php");
        exit();
    } else{
        $error = "Please provide valid input values";

    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

<form method="post" class="form-container">
    <h2>Add Product</h2>
    <label>Name:</label>
    <input type="text" name="name" required><br>
    
    <label>Quantity:</label>
    <input type="number" name="quantity" required><br>

     <label>Category:</label>
    <input type="text" name="category" required><br>
    
    <label>Price:</label>
    <input type="number" step="0.01" name="price" required><br>
    
    <input type="submit" value="Add Product" class="btn add">
</form>

</body>
</html>

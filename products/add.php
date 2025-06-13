<?php
include '../config/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizing and validating user input
    $category_id = intval($_POST['category_id']);
    $name = htmlspecialchars(trim($_POST['name']));
    $qty = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

        // If correct then insert
    if($name && $qty !==false && $price !==false){
        $stmt = $conn->prepare("INSERT INTO products (name, quantity, price) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $name, $qty, $price); // s= string
        $stmt->execute();
        $success = "Product added successfully.";
         header("Location: ../products/add.php");
        exit();
    } else{
        $error = "Please provide valid input values";

    }
}
// Fetch products for dropdown
$categories = $conn->query("SELECT category_id, name FROM categories");

?>



<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sales.css">
     <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>

        <?php include '../includes/sidebar.php'; ?>

       <!-- Alert Messages -->
        <?php if (!empty($success)): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <?= $success ?>
            </div>
        <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <div class="main-content">
         <form method="post" class="form-container">
        <h2>Add Product</h2>
        <label>Name:</label>
        <input type="text" name="name" required><br>
        
        <label>Quantity:</label>
        <input type="number" name="quantity" required><br>

        <label for="category_id">Category</label><br>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php
                $cat_res = $conn->query("SELECT * FROM categories");
                while ($cat = $cat_res->fetch_assoc()) {
                    echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                }
                ?>
            </select>


        <label>Price:</label>
        <input type="number" step="0.01" name="price" required><br>
        
        <input type="submit" value="Add Product" class="btn add">
    </form>

    </div>
   
</body>
</html>

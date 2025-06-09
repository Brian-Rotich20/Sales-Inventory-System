<?php include '../config/db.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
      <?php include '../includes/sidebar.php'; ?>
      <div style="margin-left: 220px; padding: 20px;">
     <a href="../products/add.php" class='btn add'>Add Products</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>

        <?php
        $result = $conn ->query("SELECT * FROM products");
        while($row = $result->fetch_assoc()){
            echo "<tr>
                     <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['price']}</td>
                        <td>
                            <a href='../products/edit.php?id={$row['id']}' class='btn edit'>Edit</a> 
                            <a href='../products/delete.php?id={$row['id']}' class='btn delete' onclick=\"return confirm('Delete this item?')\">Delete</a>
                        </td>
                 </tr>"; 
        }
        ?>
    </table>
      </div>
</body>
</html>
   
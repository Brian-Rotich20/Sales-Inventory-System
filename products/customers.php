
<?php
include '../config/db.php'; // DB connection

$sql = "SELECT 
            customer_name, 
            COUNT(*) AS total_sales, 
            SUM(quantity_sold) AS total_quantity 
        FROM sales 
        GROUP BY customer_name";

$result = $conn->query($sql);
// condition to handle form submission
if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $conn->query("INSERT INTO customers (name, email, phone, address) 
                  VALUES ('$name', '$email', '$phone', '$address')");
}

// Fetch customers
$result = $conn->query("SELECT * FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Tracking</title>
     <?php include '../includes/sidebar.php'; ?>
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/customers.css">
    <link rel="stylesheet" href="../css/sales.css">
  
   
</head>
<body>
<div class="main-content">
    
<h2>Add New Customer</h2>
<form method="post">
    <input type="text" name="name" placeholder="Customer Name" required>
    <input type="email" name="email" placeholder="Customer Email" required>
    <input type="tel" name="phone" placeholder="Phone Number" required>
    <input type="text" name="address" placeholder="Location Address" required>
    <button type="submit" name="add_customer">Add Customer</button>
</form>

<div class="export-btns">
    <button onclick="window.location.href='export_csv.php'">Export to CSV</button>
    <button onclick="window.location.href='export_pdf.php'">Export to PDF</button>
</div>

<h2>Customer List</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>

        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['customer_id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>

        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</div>

</body>
</html>

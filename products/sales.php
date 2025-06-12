<?php
include '../config/db.php';

// Insert sale
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $customer = htmlspecialchars(trim($_POST['customer']));

    // Check available stock
    $result = $conn->query("SELECT quantity FROM products WHERE id = $product_id");
    $product = $result->fetch_assoc();

    if ($product && $product['quantity'] >= $quantity) {
        // Insert into sales table
        $conn->query("INSERT INTO sales (product_id, quantity_sold, customer_name) VALUES ($product_id, $quantity, '$customer')");
        // Update product quantity
        $conn->query("UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id");
        $success = "Sale recorded successfully.";
    } else {
        $error = "Insufficient stock.";
    }
}

// Fetch products for dropdown
$products = $conn->query("SELECT id, name FROM products");

// Fetch all sales
$sales = $conn->query("
    SELECT s.id, p.name AS product_name, s.quantity_sold, s.customer_name, s.sale_date 
    FROM sales s 
    JOIN products p ON s.product_id = p.id
    ORDER BY s.sale_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Management</title>
     <link rel="stylesheet" href="../css/sales.css">
     <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- Include Sidebar -->
<?php include '../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-chart-line"></i>Sales Management</h1>
        </div>

        <!-- Alert Messages -->
        <?php if (!empty($success)): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <?= $success ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- Record Sale Form -->
        <div class="form-container">
            <div class="form-header">
                <i class="fas fa-plus-circle"></i>
                <h2>Record New Sale</h2>
            </div>

            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="product_id">Product</label>
                        <select name="product_id" id="product_id" class="form-control" required>
                            <option value="">Select Product</option>
                            <?php while($row = $products->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity Sold</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required min="1" placeholder="Enter quantity">
                    </div>

                    <div class="form-group">
                        <label for="customer">Customer Name</label>
                        <input type="text" name="customer" id="customer" class="form-control" required placeholder="Enter customer name">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Record Sale
                </button>
            </form>
        </div>

        <!-- Sales History Table -->
        <div class="table-container">
            <div class="table-header">
                <h3><i class="fas fa-history"></i>Sales History</h3>
            </div>

            <div class="table-wrapper">
                <?php if ($sales->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Qty Sold</th>
                                <th>Customer</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($sale = $sales->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $sale['id'] ?></td>
                                    <td><?= htmlspecialchars($sale['product_name']) ?></td>
                                    <td><?= $sale['quantity_sold'] ?></td>
                                    <td><?= htmlspecialchars($sale['customer_name']) ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($sale['sale_date'])) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-chart-line"></i>
                        <h3>No Sales Records</h3>
                        <p>Start by recording your first sale above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
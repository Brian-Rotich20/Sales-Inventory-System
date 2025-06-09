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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Main Container */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            border-left: 5px solid #3b82f6;
        }

        .page-header h1 {
            color: #1e293b;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header h1 i {
            color: #3b82f6;
            font-size: 1.8rem;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        .alert.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert.error {
            background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%);
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert i {
            font-size: 1.2rem;
        }

        /* Form Styles */
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-header h2 {
            color: #1e293b;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .form-header i {
            color: #3b82f6;
            font-size: 1.3rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control:hover {
            border-color: #9ca3af;
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-header {
            padding: 25px 30px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid #e5e7eb;
        }

        .table-header h3 {
            color: #1e293b;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-header i {
            color: #3b82f6;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        th {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
        }

        tr:hover {
            background: #f8fafc;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 15px;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            color: #374151;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 260px;
                padding: 25px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px 15px;
                padding-top: 80px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .page-header {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 1.6rem;
            }

            .form-container,
            .table-container {
                padding: 20px;
            }

            .table-header {
                padding: 20px;
            }

            th, td {
                padding: 12px 15px;
            }

            .table-wrapper {
                border-radius: 8px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 15px 10px;
                padding-top: 70px;
            }

            .page-header,
            .form-container {
                padding: 15px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            th, td {
                padding: 10px 12px;
                font-size: 0.85rem;
            }

            .btn {
                padding: 10px 20px;
                width: 100%;
            }
        }
    </style>
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
<?php include '../config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Notifications</title>

</head>
<body>
      <!-- Php for stock alert -->
        <?php
        // Those stock in critical
        $criticalStockQuery = "SELECT name, location from products where quantity < 1";
        $criticalStock = $conn->query($criticalStockQuery);

        // Those expiring soon
        $expiryWarningQuery = "SELECT name, expiry_date, batch_number FROM products WHERE expiry_date IS NOT NULL AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
        $expiringProducts = $conn->query($expiryWarningQuery);
        ?>
        <!-- Alerts & Actions -->
<div class="alerts-section">
    <div class="alert-card">
        <h3 style="margin-bottom: 20px; color: #333;">Critical Alerts</h3>

        <?php while ($item = $criticalStock->fetch_assoc()): ?>
        <div class="alert-item critical">
            <div class="alert-icon critical">
                <i class="fas fa-exclamation"></i>
            </div>
            <div>
                <strong>Stock Out Alert</strong><br>
                <span style="color: #666;"><?= htmlspecialchars($item['name']) ?> - <?= htmlspecialchars($item['location']) ?></span>
            </div>
        </div>
        <?php endwhile; ?>

        <?php while ($item = $expiringProducts->fetch_assoc()): ?>
        <div class="alert-item warning">
            <div class="alert-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <strong>Expiry Warning</strong><br>
                <span style="color: #666;"><?= htmlspecialchars($item['batch_number']) ?> expires on <?= htmlspecialchars($item['expiry_date']) ?></span>
            </div>
        </div>
        <?php endwhile; ?>

        <!-- Static example -->
        <div class="alert-item info">
            <div class="alert-icon info">
                <i class="fas fa-truck"></i>
            </div>
            <div>
                <strong>Shipment Delayed</strong><br>
                <span style="color: #666;">PO #12458 delayed by 2 days</span>
            </div>
        </div>
    </div>

    <!-- Recommended Actions Card -->
    <div class="alert-card">
        <h3 style="margin-bottom: 20px; color: #333;">Recommended Actions</h3>

        <div class="alert-item info">
            <div class="alert-icon info">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div>
                <strong>Reorder Suggestion</strong><br>
                <span style="color: #666;">Samsung Galaxy S24 - 200 units</span>
            </div>
        </div>

        <div class="alert-item warning">
            <div class="alert-icon warning">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div>
                <strong>Transfer Required</strong><br>
                <span style="color: #666;">Move 50 units from Warehouse A to B</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
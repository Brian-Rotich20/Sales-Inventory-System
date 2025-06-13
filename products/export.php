<?php
include('../config/db.php');

$type = $_GET['type'] ?? 'products';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=' . $type . '.csv');

$output = fopen('php://output', 'w');

if ($type == 'products') {
    fputcsv($output, ['ID', 'Name', 'Quantity', 'Price']);  
    $result = $conn->query("SELECT * FROM products");

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['id'], $row['name'], $row['quantity'], $row['price']]);
    }
} else if ($type == 'customers') {
    fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Address']);  
    $result = $conn->query("SELECT * FROM customers");

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['customer_id'], $row['name'], $row['email'], $row['phone'], $row['address']]);
    }
}

fclose($output);
exit;

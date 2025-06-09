<?php include '../config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System - Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <!-- <link rel="stylesheet" href="../css/alert.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>
    
    <!-- Toggle Button for Mobile -->
    <button class="toggle-btn" id="toggleBtn">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Main Content -->
    <div class="content" id="mainContent">
        <?php include '../includes/navbar.php'; ?>

        <?php
            // Queries for summary boxes
            $totalProductsResult = $conn->query("SELECT COUNT(*) AS total FROM products");
            $totalProducts = $totalProductsResult->fetch_assoc()['total'];

            $totalCategoriesResult = $conn->query("SELECT COUNT(DISTINCT category) AS total FROM products");
            $totalCategories = $totalCategoriesResult->fetch_assoc()['total'];

            $totalQuantityResult = $conn->query("SELECT SUM(quantity) AS total FROM products");
            $totalQuantity = $totalQuantityResult->fetch_assoc()['total'];

            $lowStockThreshold = 5;
            $lowStockResult = $conn->query("SELECT COUNT(*) AS total FROM products WHERE quantity < $lowStockThreshold");
            $lowStockCount = $lowStockResult->fetch_assoc()['total'];
        ?>
       <!-- Add this greeting section before the stats-container -->
 
   <div class="home-container">
    <div class="greeting">
        <!-- Greeting Content Section -->
        <!-- <div class="greeting-content">
            <div class="greeting-image">
                <img src="../assets/greetings.png" alt="Welcome Dashboard" />
            </div>
            <div class="greeting-text">
                <h2>Welcome back, <span class="user-name"><?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin' ?></span>!</h2>
                <p>Here's your inventory overview for today</p>
                <div class="greeting-time">
                    <i class="fas fa-clock"></i>
                    <span id="current-time"></span>
                </div>
            </div>
        </div> -->

        <!-- Stats Container Section -->
        <div class="stats-container">
            <div class="stat-card green">
                <h3><i class="fas fa-boxes"></i> Total Products</h3>
                <p><?= $totalProducts ?></p>
            </div>

            <div class="stat-card blue">
                <h3><i class="fas fa-tags"></i> Total Categories</h3>
                <p><?= $totalCategories ?></p>
            </div>

            <div class="stat-card orange">
                <h3><i class="fas fa-warehouse"></i> Total Stock</h3>
                <p><?= $totalQuantity ?></p>
            </div>

            <div class="stat-card red">
                <h3><i class="fas fa-exclamation-triangle"></i> Low Stock</h3>
                <p><?= $lowStockCount ?></p>
            </div>
        </div>
    </div>
</div>

        <?php
            // Fetch sales summary for pie chart
            $data = [];
            $sql = "SELECT p.name, SUM(s.quantity_sold) as total_sold 
                    FROM sales s 
                    JOIN products p ON s.product_id = p.id 
                    GROUP BY s.product_id
                    LIMIT 10"; // Limit to top 10 for better chart readability
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            // Fetch weekly sales data
            $query = "
                SELECT 
                    DATE(sale_date) as day, 
                    SUM(quantity_sold) as total 
                FROM sales 
                WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                GROUP BY day 
                ORDER BY day ASC
            ";

            $result = $conn->query($query);
            $labels = [];
            $totals = [];

            while ($row = $result->fetch_assoc()) {
                $labels[] = date('M j', strtotime($row['day'])); // Format date nicely
                $totals[] = $row['total'];
            }
        ?>

        <!-- Charts Section -->
        <div class="chart-wrapper">
            <div class="chart-box">
                <canvas id="salesChart"></canvas>
            </div>
            <div class="chart-box">
                <canvas id="weeklySalesChart"></canvas>
            </div>
        </div>
    </div>

    <script>
    // Sidebar toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            mainContent.classList.toggle('sidebar-open');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking on links (mobile)
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                mainContent.classList.remove('sidebar-open');
            }
        });

        // Chart configurations
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        };

        // Pie chart for sales summary
        const pieCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut', // Changed to doughnut for better mobile display
            data: {
                labels: <?= json_encode(array_column($data, 'name')) ?>,
                datasets: [{
                    label: 'Total Sold',
                    data: <?= json_encode(array_column($data, 'total_sold')) ?>,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                        '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                        '#4BC0C0', '#36A2EB'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: {
                        display: true,
                        text: 'Sales Summary by Product',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: 20
                    }
                }
            }
        });

        // Bar chart for weekly sales
        const barCtx = document.getElementById('weeklySalesChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Daily Sales',
                    data: <?= json_encode($totals) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: {
                        display: true,
                        text: 'Weekly Sales Summary',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: 20
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            precision: 0,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
    </script>
</body>
</html>
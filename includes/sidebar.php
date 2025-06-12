<?php include '../config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<!-- Toggle Button -->
<button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo Section -->
    <div class="logo-container">
        <!-- <img src="../assets/logo.png" alt="Inventory Logo" class="sidebar-logo"> -->
        <h2>Inova Ltd</h2>
    </div>

    <!-- Navigation Menu -->
    <ul>
        <li>
            <a href="../home.php" class="<?= $currentPage == 'home.php' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Home
            </a>
        </li>
        <li>
            <a href="../auth/dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="../products/AllProducts.php" class="<?= $currentPage == 'AllProducts.php' ? 'active' : '' ?>">
                <i class="fas fa-boxes"></i> Products
            </a>
        </li>
        <li>
            <a href="../products/category.php" class="<?= $currentPage == 'category.php' ? 'active' : '' ?>">
                <i class="fas fa-boxes"></i> Category
            </a>
        </li>
        <li>
            <a href="../products/sales.php" class="<?= $currentPage == 'sales.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Sales
            </a>
        </li>
        <li>
            <a href="../products/customers.php" class="<?= $currentPage == 'customers.php' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Customers
            </a>
        </li>
        <li>
            <a href="../products/add.php" class="<?= $currentPage == 'add.php' ? 'active' : '' ?>">
                <i class="fas fa-plus-circle"></i> Add Product
            </a>
        </li>
        <li>
            <a href="../auth/notification.php" class="<?= $currentPage == 'notification.php' && basename(dirname($_SERVER['PHP_SELF'])) == 'notifications' ? 'active' : '' ?>">
                <i class="fas fa-bell"></i> Notifications
            </a>
        </li>
        <li>
            <a href="../auth/account.php" class="<?= $currentPage == 'account.php' ? 'active' : '' ?>">
                <i class="fas fa-user-cog"></i> Accounts
            </a>
        </li>
          <li>
            <a href="../accounts/settings.php" class="<?= $currentPage == 'settings.php' ? 'active' : '' ?>">
                <i class="fas fa-user-cog"></i> Settings
            </a>
        </li>

        <li>
            <a href="../accounts/logout.php" class="<?= $currentPage == 'logout.php' ? 'active' : '' ?>">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
    
    // Prevent body scroll when sidebar is open on mobile
    if (window.innerWidth <= 768) {
        document.body.style.overflow = sidebar.classList.contains("active") ? "hidden" : "";
    }
}

function closeSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    document.body.style.overflow = "";
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.querySelector(".toggle-btn");
    
    if (window.innerWidth <= 768 && 
        !sidebar.contains(event.target) && 
        !toggleBtn.contains(event.target) && 
        sidebar.classList.contains("active")) {
        closeSidebar();
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        document.getElementById("sidebar").classList.remove("active");
        document.getElementById("sidebarOverlay").classList.remove("active");
        document.body.style.overflow = "";
    }
});
</script>

</body>
</html>
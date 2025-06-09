<?php include '../config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Modern Sidebar Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            overflow-x: hidden;
        }

        /* Toggle Button */
        .toggle-btn {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 15px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Main Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 0;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        /* Logo Section */
        .logo-container {
            padding: 30px 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .logo-container h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f1f5f9;
            margin: 0;
            letter-spacing: 0.5px;
        }

        /* Navigation */
        .sidebar ul {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }

        .sidebar ul li {
            margin: 0;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 25px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            border-left: 3px solid transparent;
        }

        .sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #f1f5f9;
            border-left-color: #3b82f6;
            transform: translateX(5px);
        }

        .sidebar ul li a.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.05) 100%);
            color: #60a5fa;
            border-left-color: #3b82f6;
            font-weight: 600;
        }

        .sidebar ul li a i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* Active state glow effect */
        .sidebar ul li a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(180deg, #3b82f6, #1d4ed8);
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Main Content Area */
        .content {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                width: 260px;
            }
            
            .content {
                margin-left: 260px;
            }
        }

        @media (max-width: 768px) {
            .toggle-btn {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding-top: 80px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
            }
            
            .toggle-btn {
                top: 15px;
                left: 15px;
                padding: 10px 12px;
            }
            
            .logo-container {
                padding: 25px 20px;
            }
            
            .sidebar ul li a {
                padding: 14px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
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
        <h2>Inova. Pro</h2>
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
            <a href="../notifications/index.php" class="<?= $currentPage == 'index.php' && basename(dirname($_SERVER['PHP_SELF'])) == 'notifications' ? 'active' : '' ?>">
                <i class="fas fa-bell"></i> Notifications
            </a>
        </li>
        <li>
            <a href="../auth/account.php" class="<?= $currentPage == 'account.php' ? 'active' : '' ?>">
                <i class="fas fa-user-cog"></i> Accounts
            </a>
        </li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
        <li>
            <a href="../account/settings.php" class="<?= $currentPage == 'settings.php' ? 'active' : '' ?>">
                <i class="fas fa-user-cog"></i> Settings
            </a>
        </li>
    <?php endif; ?>

        <li>
            <a href="../logout.php" class="<?= $currentPage == 'logout.php' ? 'active' : '' ?>">
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
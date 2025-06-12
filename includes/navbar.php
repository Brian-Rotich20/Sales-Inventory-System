<html>
<body>

<!-- includes/navbar.php -->
<link rel="stylesheet" href="../css/navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<nav class="top-navbar">
  <div class="nav-left">
    <!-- <i class="fas fa-bars menu-toggle" onclick="toggleSidebar()"></i> -->
    <input type="text" placeholder="Search..." class="search-bar">
  </div>

  <div class="nav-center">
    <span id="date-time"></span>
  </div>

  <div class="nav-right"> 
    <i class="fas fa-bell notification-icon"></i>
    
    <div class="user-dropdown">
      <img src="../assets/profile.jpg" alt="User" class="user-avatar" onclick="toggleDropdown()">
    </div>

    <button class="mode-toggle" onclick="toggleMode()">
      <i class="fas fa-sun" id="mode-icon"></i>
    </button>

  </div>
</nav>

<script>
function toggleDropdown() {
  document.getElementById('dropdown').classList.toggle('show');
}

function toggleSidebar() {
  document.body.classList.toggle('sidebar-collapsed');
}

function toggleMode() {
  const body = document.body;
  const icon = document.getElementById("mode-icon");
  body.classList.toggle("dark-mode");
  icon.classList.toggle("fa-sun");
  icon.classList.toggle("fa-moon");
}

// Display date and time
function updateDateTime() {
  const now = new Date();
  document.getElementById('date-time').innerText = now.toLocaleString();
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>
</body>
</html>
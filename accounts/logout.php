
<!-- logout.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirm Logout</title>
  <link rel="stylesheet" href="../css/logout.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
  <div class="logout-container">
    <h2>Are you sure you want to log out?</h2>
    <div class="button-group">
      <form action="logout_confirm.php" method="post">
        <button type="submit" class="btn logout">Yes, Log Me Out</button>
      </form>
      <a href="../auth/dashboard.php" class="btn cancel">No, Take Me Back</a>
    </div>
  </div>
</body>
</html>

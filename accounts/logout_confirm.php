<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Set headers to prevent back navigation to cached pages
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to homepage or login page
header("Location: ../home.php");
exit();
 

?>
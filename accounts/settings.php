<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
header("Location: ../auth/login.php");
exit();
}
$userId = $_SESSION['user_id']; // Assuming user_id is stored in session after login
$message = '';
$username = '';
$email = '';
$profile_photo = '';

// Retrieve existing data first (so we can show it in form and fallback photo if not changed).
$stmt = $conn->prepare("SELECT username, email, profile_photo FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$username = $user['username']; 
$email = $user['email']; 
$profile_photo = $user['profile_photo']; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']); 
    $new_pass = trim($_POST['password']); 
    $confirm_pass = trim($_POST['confirm_pass']); 
    $image_name = $profile_photo; // fallback to existing photo
    
    // Handle photo if uploaded...
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $image_name = time() . '-' . basename($_FILES['profile_photo']['name']); 
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], "../uploads/" . $image_name);
    }
    
    // Prepare base SQL first
    $query = "UPDATE users SET username = ?, email = ?, profile_photo = ?";
    $types = "sss";
    $params = [$username, $email, $image_name];
    
    // If password is provided and match
    if (!empty($new_pass)) {
        if ($new_pass !== $confirm_pass) {
            $message = "Passwords do not match.";
        } else {
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $query .= ", password = ?";
            $types .= "s";
            $params[] = $hashed_pass;
        }
    }
    
    $query .= " WHERE id = ?";
    $types .= "i";
    $params[] = $userId;

    if (empty($message)) { // proceed only if no password match issues
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
        } else {
            $message = "Failed to update profile.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Settings</title>
<link rel="stylesheet" href="../css/settings.css">
<link rel="stylesheet" href="../css/sidebar.css">
<link rel="stylesheet" href="../css/sales.css">
</head>
<body>

<?php include '../includes/sidebar.php'; ?>
 <div class="main-wrapper">
    
    <p><?= htmlspecialchars($message) ?></p>
    <main class="main-content">
        <h2>Account Settings</h2>
    <form action="settings.php" method="POST" enctype="multipart/form-data">
        <label>Username</label>
        <input name="username" value="<?= htmlspecialchars($username) ?>" required>

        <label>Email</label>
        <input name="email" type="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="password">New Password</label>
        <input id="password" name="password" type="password" placeholder="Enter new password if you want to change it">

        <label for="confirm_pass">Confirm Password</label>
        <input id="confirm_pass" name="confirm_pass" type="password" placeholder="Confirm new password">


        <label>Profile Photo</label>
        <?php if ($profile_photo): ?>
            <img src="../uploads/<?= htmlspecialchars($profile_photo) ?>" alt="Profile photo" style="width:100px;height:100px;"><br>
        <?php endif; ?>
        <input name="profile_photo" type="file" accept="image/*">
    
        <br><br>
        <input type="submit" value="Save">
    </form>
    </main>
</div>
</body>
</html>

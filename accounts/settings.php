<?php
include '../config/db.php';
session_start(); // This was commented out - needs to be active

// Get user ID from session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$username = $email = $profile_photo = '';
$message = "";

// Get user data
$stmt = $conn->prepare("SELECT username, email, profile_photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Check if user was found
if (!$user) {
    // Handle case where user doesn't exist
    $message = "User not found.";
    $user = ['username' => '', 'email' => '', 'profile_photo' => '']; // Default values
} else {
    // Set variables from user data
    $username = $user['username'];
    $email = $user['email'];
    $profile_photo = $user['profile_photo'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $image_name = $user['profile_photo'] ?? ''; // Use null coalescing operator

    // Handle image upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $target_dir = "../uploads/";
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $image_name = time() . '_' . basename($_FILES['profile_photo']['name']);
        $target_file = $target_dir . $image_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
                // File uploaded successfully
            } else {
                $message = "Failed to upload image!";
                $image_name = $user['profile_photo'] ?? ''; // fallback to old
            }
        } else {
            $message = "Invalid image type! Only JPG, JPEG, PNG & GIF files are allowed.";
            $image_name = $user['profile_photo'] ?? ''; // fallback to old
        }
    }

    // Update the database only if user exists
    if ($user) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_photo = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $image_name, $user_id);
        
        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
            // Update the user array with new values
            $user['username'] = $username;
            $user['email'] = $email;
            $user['profile_photo'] = $image_name;
            // Update display variables
            $profile_photo = $image_name;
        } else {
            $message = "Failed to update profile.";
        }
        $stmt->close();
    }
}
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="../css/settings.css">
     <link rel="stylesheet" href="../css/sidebar.css">
    <title>Settings Page</title>
</head>
<body>
    <div class="main-wrapper">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main-content">
            <div class="settings-container">
                <h2>Account Settings</h2>
                <?php if (!empty($message)) echo "<p class='status-msg'>" . htmlspecialchars($message) . "</p>"; ?>

                <form action="settings.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Profile Photo</label><br>
                        <?php if (!empty($profile_photo)): ?>
                            <img src="../uploads/<?= htmlspecialchars($profile_photo) ?>" alt="Profile" class="profile-photo-preview">
                        <?php endif; ?>
                        <input type="file" name="profile_photo" accept="image/*">
                    </div>

                    <button type="submit" class="btn save">Save Changes</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
<?php


 include '../config/db.php'; 

session_start();
// $_SESSION['user_id'] = users['id'];

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $query = ("SELECT * FROM users WHERE id = $id");
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result)> 0){
         $user = $mysql_fetch_assoc($result);
        }elseif{
            echo"User not found.";
        }

}

// $user_id = $_SESSION['id'];

$message = '';

// Fetch user info
$query = $conn->prepare("SELECT username, email, profile_photo FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($username, $email, $profile_photo);
$query->fetch();
$query->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);

    // Handle image upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $target_dir = "../uploads/";
        $image_name = time() . '_' . basename($_FILES['profile_photo']['name']);
        $target_file = $target_dir . $image_name;

        // Validate image
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed)) {
            move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file);
        } else {
            $message = "Invalid image type!";
            $image_name = $profile_photo; // fallback to old
        }
    } else {
        $image_name = $profile_photo;
    }

    // Update user
    
    $update = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_photo = ? WHERE id = ?");
    $update->bind_param("sssi", $new_username, $new_email, $image_name, $user_id);
    if ($update->execute()) {
        $message = "Profile updated successfully.";
        $username = $new_username;
        $email = $new_email;
        $profile_photo = $image_name;
         
    } else {
        $message = "Failed to update profile.";
    }
    $update->close();
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
                <?php if (!empty($message)) echo "<p class='status-msg'>$message</p>"; ?>

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
                        <?php if ($profile_photo): ?>
                            <img src="../uploads/<?= $profile_photo ?>" alt="Profile" class="profile-photo-preview">
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
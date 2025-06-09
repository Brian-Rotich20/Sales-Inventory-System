<?php
include '../config/db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hash);

        if ($stmt->execute()) {
            $success = "Registration successful. <a href='login.php'>Login</a>";
        } else {
            $error = "Username or Email already taken.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body style="font-family: Arial; background-color: #f4f4f4; padding: 40px; text-align: center;">

    <div style="max-width: 400px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc;">
        <h2>Register</h2>

        <?php if ($error): ?>
            <div style="color: red; margin-bottom: 10px;"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="color: green; margin-bottom: 10px;"><?= $success ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" placeholder="Username" required style="width: 100%; padding: 10px; margin-bottom: 10px;"><br>
            <input type="email" name="email" placeholder="Email" required style="width: 100%; padding: 10px; margin-bottom: 10px;"><br>
            <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 10px; margin-bottom: 10px;"><br>
            <input type="password" name="confirm" placeholder="Confirm Password" required style="width: 100%; padding: 10px; margin-bottom: 20px;"><br>
            <input type="submit" value="Register" style="width: 100%; background-color: #28a745; color: white; border: none; padding: 12px; cursor: pointer;">
        </form>
    </div>

</body>
</html>

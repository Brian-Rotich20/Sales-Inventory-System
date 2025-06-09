

<?php
include '../config/db.php';
// session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hash);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: ./dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body style="font-family: Arial; background-color: #f4f4f4; padding: 40px; text-align: center;">

    <div style="max-width: 400px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc;">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div style="color: red; margin-bottom: 10px;"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" placeholder="Username" required style="width: 100%; padding: 10px; margin-bottom: 10px;"><br>
            <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 10px; margin-bottom: 20px;"><br>
            <input type="submit" value="Login" style="width: 100%; background-color: #007bff; color: white; border: none; padding: 12px; cursor: pointer;">
            <p>If not registered, then click <a href="../auth/register.php">register</p>
        </form>
    </div>

</body>
</html>

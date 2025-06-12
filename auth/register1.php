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
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
     
<section class="vh-100" style="background-color: #eee;">
  <div class="container h-100">

    <!-- Alert messages -->
    <div class="pt-3">
        <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success text-center"><?= $success ?></div>
        <?php endif; ?>
    </div>

    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">

              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                <p class="text-center h1 fw-bold mb-5 mt-4">Sign up</p>

                <form method="POST">

                  <div class="form-outline mb-4">
                    <label class="form-label" for="username">Your Name</label>
                    <input type="text" id="username" name="username" class="form-control" required />
                  </div>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="email">Your Email</label>
                    <input type="email" id="email" name="email" class="form-control" required />
                  </div>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required />
                  </div>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="confirm">Repeat your password</label>
                    <input type="password" id="confirm" name="confirm" class="form-control" required />
                  </div>

                  <div class="form-check d-flex justify-content-center mb-4">
                    <input class="form-check-input me-2" type="checkbox" required />
                    <label class="form-check-label">
                      I agree to the <a href="#">Terms of service</a>
                    </label>
                  </div>

                  <div class="d-flex justify-content-center mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                  </div>

                </form>
              </div>

              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                <img src="../assets/greetings3.jpg" class="img-fluid" alt="Sample image" />
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

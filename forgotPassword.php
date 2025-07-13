<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = mysqli_prepare($connect, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // Generate secure token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save token and expiry
        $update = mysqli_prepare($connect, "UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
        mysqli_stmt_bind_param($update, "sss", $token, $expiry, $email);
        mysqli_stmt_execute($update);

        // Send email (simplified, make sure mail() works on your server)
        $resetLink = "http://yourdomain.com/resetPassword.php?token=$token&email=$email";
        $subject = "Password Reset Request";
        $message = "Click here to reset your password: $resetLink";
        $headers = "From: noreply@yourdomain.com";

        mail($email, $subject, $message, $headers);

        $_SESSION['msg'] = "🔐 Password reset link has been sent to your email.";
        header("Location: forgotPassword.php");
        exit;
    } else {
        $_SESSION['msg'] = "❌ Email not found!";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card p-4 mx-auto" style="max-width: 400px;">
    <h3 class="text-center">Forgot Password</h3>

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-info text-center"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Enter your registered email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
    </form>
  </div>
</div>
</body>
</html>

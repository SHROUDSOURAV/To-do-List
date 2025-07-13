<?php
session_start();
include 'connection.php';

if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];

    // Validate token
    $stmt = mysqli_prepare($connect, "SELECT * FROM users WHERE email = ? AND reset_token = ? AND token_expiry > NOW()");
    mysqli_stmt_bind_param($stmt, "ss", $email, $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$user = mysqli_fetch_assoc($result)) {
        die("❌ Invalid or expired token.");
    }
} else {
    die("❌ Invalid request.");
}

// If form submitted to reset password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPass = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($newPass !== $confirm) {
        $_SESSION['msg'] = "❌ Passwords do not match!";
    } else {
        $hashed = password_hash($newPass, PASSWORD_DEFAULT);

        $update = mysqli_prepare($connect, "UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?");
        mysqli_stmt_bind_param($update, "ss", $hashed, $email);
        mysqli_stmt_execute($update);

        $_SESSION['msg'] = "✅ Password updated successfully!";
        header("Location: login.php");
        exit;
    }
}
?>

<!-- Reset Password Form -->
<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card p-4 mx-auto" style="max-width: 400px;">
    <h3 class="text-center">Reset Your Password</h3>

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-warning text-center"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>New Password</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Update Password</button>
    </form>
  </div>
</div>
</body>
</html>
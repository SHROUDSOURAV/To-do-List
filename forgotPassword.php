<?php
session_start();
include 'connection.php';

$generatedLink = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = mysqli_prepare($connect, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // Generate token and expiry
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        // Update DB with token
        $update = mysqli_prepare($connect, "UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
        mysqli_stmt_bind_param($update, "sss", $token, $expiry, $email);
        mysqli_stmt_execute($update);

        // ✅ Generate local reset link
        $generatedLink = "http://localhost/todo/To-do-List/resetPassword.php?token=$token&email=$email";

        $_SESSION['msg'] = "✅ Password reset link generated. Use the link below.";
    } else {
        $_SESSION['msg'] = "❌ Email not found.";
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
  <div class="card p-4 mx-auto" style="max-width: 500px;">
    <h3 class="text-center mb-3">Forgot Password</h3>

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-info text-center"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

    <?php if (!empty($generatedLink)): ?>
        <div class="alert alert-success">
            <strong>Reset Link:</strong><br>
            <a href="<?= $generatedLink ?>"><?= $generatedLink ?></a>
            <br><small>This link is valid for 10 minutes.</small>
        </div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Enter your registered email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Generate Reset Link</button>
    </form>
  </div>
</div>
</body>
</html>

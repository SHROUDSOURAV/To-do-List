<?php
session_start();
include 'connection.php';

$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

if (!$token || !$email) {
    die("<div class='text-danger text-center mt-5'>Invalid reset link.</div>");
}

// Check token validity
$stmt = mysqli_prepare($connect, "SELECT * FROM users WHERE email = ? AND reset_token = ?");
mysqli_stmt_bind_param($stmt, "ss", $email, $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$user = mysqli_fetch_assoc($result)) {
    die("<div class='text-danger text-center mt-5'>Invalid or expired token.</div>");
}

if (strtotime($user['token_expiry']) < time()) {
    die("<div class='text-danger text-center mt-5'>Token has expired. Please request a new one.</div>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPass = $_POST['new_password'];
    $confirmPass = $_POST['confirm_password'];

    if ($newPass !== $confirmPass) {
        $error = "❌ Passwords do not match.";
    } elseif (strlen($newPass) < 8) {
        $error = "❌ Password must be at least 8 characters.";
    } else {
        // ❗ Storing password directly (insecure for production)
        $update = mysqli_prepare($connect, "UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?");
        mysqli_stmt_bind_param($update, "ss", $newPass, $email);
        mysqli_stmt_execute($update);

        $success = "✅ Password successfully reset. Redirecting to login...";
        echo "<script>
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 5000);
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    #strengthBar {
      height: 8px;
      background: lightgray;
      margin-top: 5px;
      border-radius: 5px;
    }
    #strengthBar div {
      height: 100%;
      border-radius: 5px;
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card p-4 mx-auto" style="max-width: 450px;">
    <h3 class="text-center mb-3">Reset Your Password</h3>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger text-center"><?= $error; ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
      <div class="alert alert-success text-center"><?= $success; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <div class="input-group">
          <input type="password" name="new_password" id="new_password" class="form-control" required oninput="checkStrength(this.value)">
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password')">👁</button>
        </div>
        <div id="strengthBar"><div id="strengthFill"></div></div>
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <div class="input-group">
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password')">👁</button>
        </div>
      </div>

      <button type="submit" class="btn btn-success w-100">Update Password</button>
    </form>
  </div>
</div>

<script>
function togglePassword(id) {
  const input = document.getElementById(id);
  input.type = input.type === "password" ? "text" : "password";
}

function checkStrength(password) {
  const bar = document.getElementById("strengthFill");
  let strength = 0;

  if (password.length >= 6) strength++;
  if (password.match(/[A-Z]/)) strength++;
  if (password.match(/[0-9]/)) strength++;
  if (password.match(/[@$!%*#?&]/)) strength++;

  let width = (strength / 4) * 100;
  let color = "red";

  if (strength === 2) color = "orange";
  if (strength === 3) color = "gold";
  if (strength === 4) color = "green";

  bar.style.width = width + "%";
  bar.style.backgroundColor = color;
}
</script>
</body>
</html>

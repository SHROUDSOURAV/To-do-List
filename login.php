<!-- login page to take username and password -->
<!-- backend working of this credentials done in loginProcess.php-->
<?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-success text-center"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif; ?>

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Page TO-DO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="./styles/loginStyle.css" rel="stylesheet">
</head>
<body>
    <div class="card">
        <h2><i class="bi bi-person-fill-lock"></i>Login</h2>

        <!-- ✅ Display Session Alert Message if Exists -->
        <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-danger text-center d-flex align-items-center gap-2 justify-content-center fw-semibold" role="alert">
            <?php 
            echo $_SESSION['msg']; 
            session_destroy(); // Clear session after displaying message
            ?>
        </div>
        <?php endif; ?>
        <form action="loginProcess.php" method="POST">          
            <div class="form-group">
                <label for="name" class="form-label">
                <i class="bi bi-person-circle"></i> Username
                </label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter your username" required>
            </div>
            <div class="form-group position-relative">
                <label for="password" class="form-label">
                    <i class="bi bi-lock-fill"></i> Password
                </label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                    <span class="input-group-text">
                        <i class="bi bi-eye" id="togglePassword" style="cursor: pointer;" onclick="togglePassword()"></i>
                    </span>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-login btn-lg">Login</button>
            </div>
            
        </form>
        <div class="link mt-3">      
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.getElementById("togglePassword");

            if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("bi-eye");
            toggleIcon.classList.add("bi-eye-slash");
            } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("bi-eye-slash");
            toggleIcon.classList.add("bi-eye");
            }
        }
    </script>
</body>
</html>

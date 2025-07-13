<?php 
session_start();  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>To Do Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="./styles/registerStyle.css" rel="stylesheet">
</head>
<body>
    <div class="card">
        <h2><i class="bi bi-person-plus-fill"></i> Register</h2>

        <!-- Session Alert Message -->
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-danger text-center d-flex align-items-center gap-2 justify-content-center fw-semibold" role="alert">
                <?php 
                echo $_SESSION['msg']; 
                session_destroy();
                ?>
            </div>
        <?php endif; ?>

        <form action="registerProcess.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label"><i class="bi bi-person-fill me-2"></i>Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label"><i class="bi bi-envelope-fill me-2"></i>Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label"><i class="bi bi-lock-fill me-2"></i>Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    <span class="input-group-text">
                    <i class="bi bi-eye" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')" style="cursor:pointer;"></i>
                    </span>
                </div>
                <div id="password-strength" class="mt-1"></div>
            </div>


            <div class="mb-3 position-relative">
                <label for="cpassword" class="form-label"><i class="bi bi-shield-lock-fill me-2"></i>Confirm Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm password" required>
                    <span class="input-group-text">
                    <i class="bi bi-eye" id="toggleCPassword" onclick="togglePasswordVisibility('cpassword', 'toggleCPassword')" style="cursor:pointer;"></i>
                    </span>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right me-2"></i>Register</button>
            </div>
        </form>

        <!-- Styled Login Link -->
        <!-- Styled Login Link -->
        <div class="text-center mt-4">
            <p class="mb-2 text-light">Already have an account?</p>
            <a href="login.php" class="btn btn-outline-info btn-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login here
            </a>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkPasswordStrength(password) {
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            return strength;
        }

        function updateStrengthMeter() {
            const password = document.getElementById("password").value;
            const meter = document.getElementById("password-strength");
            const strength = checkPasswordStrength(password);

            if (!password) {
            meter.textContent = "";
            meter.className = "";
            } else if (strength <= 1) {
            meter.textContent = "Weak";
            meter.className = "text-danger fw-bold";
            } else if (strength === 2 || strength === 3) {
            meter.textContent = "Medium";
            meter.className = "text-warning fw-bold";
            } else {
            meter.textContent = "Strong";
            meter.className = "text-success fw-bold";
            }
        }

        function togglePasswordVisibility(id, toggleId) {
            const input = document.getElementById(id);
            const toggle = document.getElementById(toggleId);

            if (input.type === "password") {
            input.type = "text";
            toggle.classList.remove("bi-eye");
            toggle.classList.add("bi-eye-slash");
            } else {
            input.type = "password";
            toggle.classList.remove("bi-eye-slash");
            toggle.classList.add("bi-eye");
            }
        }

        // Prevent form submission if password is weak
        document.querySelector("form").addEventListener("submit", function (e) {
            const password = document.getElementById("password").value;
            const strength = checkPasswordStrength(password);

            if (strength <= 1) {
            e.preventDefault();
            alert("Password is too weak. Please choose a stronger password.");
            }
        });

        // Add keyup listener
        document.getElementById("password").addEventListener("keyup", updateStrengthMeter);
    </script>
</body>
</html>

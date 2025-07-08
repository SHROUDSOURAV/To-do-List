<!-- php file to process the the credentials of the registered user and check for validation and duplicated emails for verification -->
<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name      = $_POST['name'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password !== $cpassword) {
        $_SESSION['msg'] = "❌ Passwords do not match!";
        header('Location: register.php');
        exit;
    }

    $query = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['msg'] = "⚠️ Email already exists!";
        header('Location: register.php');
        exit;
    }

    // insert new users to database
    $insert = mysqli_query($connect, "INSERT INTO `users`(`Name`, `Email`, `Password`) VALUES ('$name','$email','$password')");
    if($insert){
        $_SESSION['msg'] = 'New User created successfully ✅';
        header('location:register.php');
    }
}
?>
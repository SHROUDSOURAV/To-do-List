<?php
session_start();
include 'connection.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $password = $_POST['password'];


    $credentials_check = mysqli_query($connect, "SELECT * FROM `users` WHERE name = '$name' AND password='$password'");

    // checks if username and password matches or not
    if(mysqli_num_rows($credentials_check) === 1){  // if matches 
        $user = mysqli_fetch_assoc($credentials_check); // converting to associative array to traverse the user details like array indexes

        // setting session variables
        $_SESSION['userId'] = $user['id'];
        $_SESSION['username'] = $user['Name'];
        $_SESSION['email'] = $user['Email'];

        header('location:app.php');  // redirect to the TODO application webpage
    }
    else{
        $_SESSION['msg'] = 'Invalid username or password';
        header('location:login.php');
    }
}
?>
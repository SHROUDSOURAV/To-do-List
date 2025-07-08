<?php
session_start();
include 'connection.php';
$userId = $_SESSION['userId'];
// checks if userId received during the loginProcess.php 
// otherwise login didn't happen properly and user redirected to login page
if(!isset($userId)){
    header('location:login.php');
    exit();
}

// handle adding tasks
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])){

    // works as defensive mechanism (prevents input of special characters
    // prevents attacks like SQL injections
    // escapes when special characters are given as input 
    $task = mysqli_real_escape_string($connect, $_POST['task']);    


    if(!empty($task)){
        // inserts the task to the database and marks as pending 
        $insert_task = mysqli_query($connect, "INSERT INTO `tasks`(`userId`, `task`, `status`) VALUES ('$userId','$task','pending')");
    }
    header('location:app.php'); // then redirect to app.php applicatio webpage
    exit();
}

?>
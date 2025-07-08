<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['userId'])){
    header('location:login.php');
    exit();
}

$userId = $_SESSION['userId'];

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // task id
    $id = $_GET['id'];

    // status checking
    $statusCheck = mysqli_query($connect, "SELECT `status` FROM `tasks` WHERE id=$id AND userId=$userId");

    // checking if there is any task based on the taskid and userid (both need to be true)
    if(mysqli_num_rows($statusCheck) > 0){
        $taskTable = mysqli_fetch_assoc($statusCheck);
        // if completed-> change to pending or vice-versa
        $statusChange = ($taskTable['status'] === 'completed') ? 'pending' : 'completed';

        // update the task status in app.php
        $statusUpdate = mysqli_query($connect, "UPDATE `tasks` SET `status` = '$statusChange' WHERE id=$id AND userId=$userId");
    }
    header('location:app.php');
    exit();
}

?>
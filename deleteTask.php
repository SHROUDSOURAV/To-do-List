<?php
session_start();
include 'connection.php';

// check if user is logged in or not
if(!isset($_SESSION['userId'])){
    header('location:login.php');
    exit();
}

$userId = $_SESSION['userId'];

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = $_GET['id'];
    // delete the task as per the id of the task
    $delete_query = mysqli_query($connect, "DELETE FROM `tasks` WHERE id=$id AND userId=$userId");
}

if($delete_query){
    header('location:app.php');
}

?>
<?php
session_start();
// check if user logged in successfully
if(!isset($_SESSION['userId'])){
    header('location:login.php');
    exit();
}
include 'connection.php';
$userId = $_SESSION['userId'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>To-Do App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="./styles/app.css" rel="stylesheet">
</head>
<body>
    <a href="logout.php" class="btn btn-danger logout">
        <i class="bi bi-box-arrow-right me-1"></i>Logout
    </a>

    <div class="container">
        <div class="card mx-auto" style="max-width: 650px;">
        <h2><i class="bi bi-list-check"></i> My To-Do List</h2>

        <!-- Add Task Form -->
        <form action="addTask.php" method="POST" class="mb-4 d-flex gap-3">
            <input type="text" name="task" class="form-control" placeholder="✍️ Add a new task..." required>
            <button type="submit" class="btn btn-add">
            <i class="bi bi-plus-circle me-1"></i> Add
            </button>
        </form>

        <!-- Display Task List -->
        <?php
        $query = mysqli_query($connect, "SELECT * FROM `tasks` WHERE userId='$userId' ORDER BY date DESC"); // new tasks appear top in the tasklist as per date

        if(mysqli_num_rows($query) == 0){
            echo "<div class='alert alert-warning text-center'>📝 No tasks added yet. Start by adding one above!</div>";
        }
        while($taskTable = mysqli_fetch_assoc($query)){
            echo "<div class='task d-flex justify-content-between align-items-center p-2 mb-2 ";
            echo ($taskTable['status'] === 'completed') ? "bg-light text-muted completed" : "bg-dark text-white";
            echo "'>";
    
            echo "<div class='d-flex align-items-center gap-2'>";
            echo "<a href='markunmarkTask.php?id=" . $taskTable['id'] . "' class='text-decoration-none'>";
            if ($taskTable['status'] === 'completed') {
                echo "<i class='bi bi-check-circle-fill text-success fs-5'></i>";
            } else {
                echo "<i class='bi bi-circle text-secondary fs-5'></i>";
            }
            echo "</a>";

            echo "<span>" . htmlspecialchars($taskTable['task']) . "</span>";
            echo "</div>";

            // Delete button shown only for pending or completed tasks
            // send the id of the user whose task you want to delete
            echo "<a href='deleteTask.php?id=" . $taskTable['id'] . "' class='btn btn-sm btn-outline-danger' title='Delete Task'>";
            echo "<i class='bi bi-trash3-fill'></i></a>";

            echo "</div>";
        }
        ?>
        </div>
    </div>
</body>
</html>

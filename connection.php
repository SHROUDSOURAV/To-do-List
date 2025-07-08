<!-- php file to connect php code with mysql database -->
<?php
$connect = mysqli_connect('localhost','root','','todo');
if(!$connect){
    echo 'DB not connected ❌';
}
?>
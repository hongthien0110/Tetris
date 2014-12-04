<?php
session_start();
include 'connect.php';
if(isset($_SESSION['user_id'])) $user_id=$_SESSION['user_id'];
$query = mysqli_query($conn,"SELECT * FROM user_status WHERE user_id = '$user_id'");
$array = mysqli_fetch_row($query);
header('Content-Type: application/json');
echo json_encode($array);
?>
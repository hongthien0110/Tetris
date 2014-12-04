<?php
session_start();
$userid = 0;
$data = "";
$score = 0;
$level = 1;
$line = 0;
include 'connect.php';
if(isset($_SESSION['user_id'])) $userid = $_SESSION['user_id'];
if(isset($_POST['saved'])) $data = $_POST['saved'];
if(isset($_POST['score'])) $score = $_POST['score'];
if(isset($_POST['level'])) $level = $_POST['level'];
if(isset($_POST['line'])) $line = $_POST['line'];
$query = mysqli_query($conn,"SELECT * FROM user_status WHERE user_id = $userid");
if (mysqli_fetch_row($query) != 0) {
	$sql_editsave = "UPDATE user_status SET saved_status='$data' WHERE user_id='$userid'";
    mysqli_query($conn, $sql_editsave);
    $sql_editscore = "UPDATE user_status SET score='$score' WHERE user_id='$userid'";
    mysqli_query($conn, $sql_editscore);;
    $sql_editlevel = "UPDATE user_status SET level='$level' WHERE user_id='$userid'";
    mysqli_query($conn, $sql_editlevel);;
    $sql_editline = "UPDATE user_status SET line='$line' WHERE user_id='$userid'";
    mysqli_query($conn, $sql_editline);;
}
else
	$user_score=mysqli_query($conn, "INSERT INTO user_status VALUES('$userid','$data','$score','$level','$line')");
echo $data;
?>
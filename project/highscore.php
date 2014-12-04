<?php
session_start();
$score = (isset($_POST['score']) ? $_POST['score'] : '');
$level = (isset($_POST['level']) ? $_POST['level'] : '');
include 'connect.php';
    $user = mysqli_query($conn, "SELECT * FROM user_score WHERE user_id=".$_SESSION['user_id']);
    if (mysqli_num_rows($user) != 0) {
        while ($row = mysqli_fetch_array($user)) {
			if ($score>$row['high_score']) mysqli_query($conn, "UPDATE user_score SET high_score=$score WHERE user_id=".$_SESSION['user_id']);
			if ($level>$row['max_level']) mysqli_query($conn, "UPDATE user_score SET max_level=$level WHERE user_id=".$_SESSION['user_id']);
			mysqli_query($conn, "UPDATE user_score SET last_score=$score WHERE user_id=".$_SESSION['user_id']);
		$highscore=$row["high_score"];
		}
	}else{
		mysqli_query($conn, "INSERT user_score VALUES(".$_SESSION['user_id'].",$score,$score,$level)");
		$highscore=$score;
	}
echo '<p class="text-danger">Highest Score<span class="pull-right">'.$highscore.'</span></p>';
?>
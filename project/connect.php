<?php
$conn = mysqli_connect('localhost','root', '');
mysqli_query($conn,"SET NAMES 'utf8'");
$db = mysqli_select_db( $conn, 'tetris');
header('Content-Type: text/html; charset=utf-8');
?>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script type="text/javascript" src="../js/js.js"></script>
<?php
session_start();
$email = (isset($_POST['email']) ? $_POST['email'] : '');
$password = (isset($_POST['password']) ? $_POST['password'] : '');
include 'connect.php';
if (isset($_POST['login'])||isset($_SESSION['username'])) {
    $log = mysqli_query($conn, "SELECT * FROM user WHERE email='$email' AND password='$password' ");
    if (mysqli_num_rows($log) != 0) {
        while ($row = mysqli_fetch_array($log)) {
            if ($row['username'] == 'Administrator') {
                //session_register('user_id');
                if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = $row['user_id'];
$_SESSION['user_id'] = $row['user_id'];
                header('Location: ./user_management/user_management.htm');
            } else {
                //session_register('username');
                if(!isset($_SESSION['user_id'])) 
$_SESSION['user_id'] = $row['user_id'];
                header('Location: game_board.php');
            }
        }
    } else{
        echo "<div class='red bold'>Wrong account or password!</div>";
	header("Refresh: 2; url='login.htm' ");
    }
}
?>
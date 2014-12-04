<script type="text/javascript" src="../js/js.js"></script>
<?php
session_start();
$email = (isset($_POST['email']) ? $_POST['email'] : '');
$username = (isset($_POST['username']) ? $_POST['username'] : '');
$password = (isset($_POST['password']) ? $_POST['password'] : '');
$confirm = (isset($_POST['confirm']) ? $_POST['confirm'] : '');
include 'connect.php';
    $user=mysqli_query($conn, "SELECT * FROM user");
    $id=array();
    $i=1;
    $id[0]=1;
    if (mysqli_num_rows($user) > 0){
        while($row=mysqli_fetch_array($user)){
            //$temp=substr($row['user_id'],strlen($row['user_id'])-3,strlen($row['user_id']));
            //if(($row['user_id']-$id[$i])>1) break;
            if(is_numeric($row['user_id'])){
                $id[$i] = $row['user_id'];
                if(($id[$i]-$id[$i-1])>1) {unset($id[$i]); break;}
                $i=$i+1;
            }  
        }
    }
    $max=$id[0];
    for($x=1;$x<count($id);$x++){
        if($id[$x]> $max) $max=$id[$x];
    }
    $user_id = $max+1;

if (isset($_POST['res'])) {
    $res = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($res) == 0) {
        if($username=='') echo "<p class='red'>Username is null !</p>";
        else if ($password=='') echo "<p class='red'>Password is null !</p>";
        else if ($password!=$confirm) echo "<p class='red'>Password confirm not right !</p>";
        else if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            mysqli_query($conn, "INSERT INTO user VALUES($user_id,'$username','$email','$password')");
            echo "<p class='blue'>Added a user success.</p>";
            echo "<script>$('#login').click();</script>";
        }
	else{
	    echo "<p class='red'>Email is not valid !!!</p>";
	}
    } else
        echo "<p class='red'>Email exists, please register another email !!</p>";
}
?>
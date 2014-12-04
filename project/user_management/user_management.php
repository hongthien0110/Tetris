<script type="text/javascript" src="../js/js.js">
    </script>
<?php
    session_start();
    include '../connect.php';
    //mysqli_query($conn, "INSERT INTO user_score VALUES($user_id,$last_score,$high_score,$max_level)"); //add high score
    //mysqli_query($conn, "SELECT * FROM user_score WHERE user_id=$user_id"); // select from user_score high score
    //mysqli_query($conn, "INSERT INTO user_status VALUES($user_id,'$saved_status',$score,$level)"); //add saved game
    //mysqli_query($conn, "SELECT * FROM user_status WHERE user_id=$user_id")); // select from user_status game status
    
    if(isset($_POST['user_id'])) $user_id=$_POST['user_id'];
    if(isset($_POST['username'])) $username=$_POST['username'];
    if(isset($_POST['email'])) $email=$_POST['email'];
    if(isset($_POST['password'])) $password=$_POST['password'];
    if(isset($_POST['add']) && $_POST['add']=='true' ){
        $add = mysqli_query($conn, "SELECT * FROM user WHERE user_id=$user_id");
        if(mysqli_num_rows($add)==0){
            if ($user_id != '') {
                if ($username == '' || $email == '' || $password == '') echo "<p class='red'>Please fill out all field.</p>";
                else {
                    mysqli_query($conn, "INSERT INTO user VALUES($user_id,'$username','$email','$password')");
                    echo '<script>$("#wrapper").load("load_user.php");  $("div#result").click();</script>';
                    echo '<p>Added a user success.</p>';
                }
            }
        }
        else{
            echo "<p>Exists user who you want to add.</p>";
        }
    }
    if(isset($_POST['del'])){
        $del = mysqli_query($conn, "SELECT * FROM user WHERE user_id=$user_id");
        if(mysqli_num_rows($del)!=0){
            if ($user_id != '') {
                if($_POST['del']=='true' && $username!=$_SESSION['user_id']){
                    mysqli_query($conn, "DELETE FROM user WHERE user_id=$user_id");
                    echo '<p>Deleted a user success.</p>';
                }
                else if ($username==$_SESSION['user_id']) echo '<p>Not exists privileged delete this user.</p>';
                echo '<script>$("#wrapper").load("load_user.php");  $("div#result").click();</script>';
            }
        }
        else{
            echo "<p>Not exists user who you want to delete.</p>";
        }
    }
    if(isset($_POST['edit'])){
        $edit = mysqli_query($conn, "SELECT * FROM user WHERE user_id=$user_id");
        if(mysqli_num_rows($edit)!=0){
            if ($user_id != '') {
                if ($username == '' || $email == '' || $password == '') echo "<p>Please fill out all field.</p>";
                else {
                    if($_POST['edit']=='true'){
                        mysqli_query($conn, "UPDATE user SET username='$username',email='$email',password='$password' WHERE user_id=$user_id");
                        echo '<p>Edited a user success.</p>';
                    }
                    echo '<script>$("#wrapper").load("load_user.php");  $("div#result").click();</script>';
                }
            }
        }
        else{
            echo "<p>Not exists user who you want to edit.</p>";
        }
    }
?>
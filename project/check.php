<?php
session_start();
$email=$_REQUEST["suggest"];
$hint="";
include 'connect.php';

if ($email !== "") {
  $email=strtolower($email);
  $query = mysqli_query($conn, "SELECT * FROM user");
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "<p class='red'>Invalid email format !</p>"; 
  }else if(mysqli_num_rows($query) != 0){
        while($row=mysqli_fetch_array($query)){
            if($row['email']==$email) {
                $hint=$row['email'];
                break;
            }
        }
    echo $hint==="" ? "<p class='green'>OK !</p>" : "<p class='red'>Availabled !!</p>";
  }
}
else{
    echo "<p class='red'></p>";
}
?>
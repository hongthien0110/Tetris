<script type="text/javascript" src="../js/js.js"></script>
<?php
session_start();
include 'connect.php';
    $user_score=mysqli_query($conn, "SELECT * FROM user, user_score WHERE user.user_id=user_score.user_id ORDER BY user_score.high_score DESC LIMIT 0,10;");
    if (mysqli_num_rows($user_score) > 0){
        while($row=mysqli_fetch_array($user_score)){
                echo "<li>".$row['username']."<span class='pull-right'>".$row['high_score']."<span></li>";
            }
        }
?>
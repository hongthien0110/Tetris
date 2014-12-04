<?php
    session_start();
    include '../connect.php';
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
    $user_id = sprintf("%03d", $max+1);
    echo $user_id;
?>
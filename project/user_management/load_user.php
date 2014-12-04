<link rel="stylesheet" type="text/css" href="../css/style.css" />
<script type="text/javascript" src="../js/js.js"></script>
<?php
    session_start();
    include '../connect.php';
    $user = mysqli_query($conn, "SELECT * FROM user");
    if(isset($_SESSION['user_id']) && $_SESSION['user_id']=="000")
    {   
        echo'<table border="1" width="100%" ><div class="info">Administrator </div>
            <tr class="table_color">
                <th width="25%">Admin ID</th>
                <th width="25%">Admin Name</th>
                <th width="25%">Email</th>
                <th width="25%">Password</th>
            </tr>';
        while ($row = mysqli_fetch_array($user)) {
            $temp = substr($row['username'],0,5);
            if($temp=='Admin'){
                echo "<tr >";
                echo "<td width='25%'>" . $row['user_id'] ."</td>";
                echo "<td width='25%'>" . $row['username'] . "</td>";
                echo "<td width='25%'>" . $row['email'] . "</td>";
                echo "<td width='25%'>" . $row['password'] . "</td>";
                echo "</tr>";
            }
        }
    echo'<table border="1" width="100%"><div class="info">User </div>
            <tr class="table_color">
                <th width="25%">User ID</th>
                <th width="25%">Username</th>
                <th width="25%">Email</th>
                <th width="25%">Password</th>
            </tr>';
    $user = mysqli_query($conn, "SELECT * FROM user");
    if (mysqli_num_rows($user)!=0){
        while ($row = mysqli_fetch_array($user)) {
            $temp = substr($row['username'],0,5);
            if($temp=='Admin') continue;
            echo "<tr>";
            echo "<td width='25%'>" . $row['user_id'] ."</td>";
            echo "<td width='25%'>" . $row['username'] . "</td>";
            echo "<td width='25%'>" . $row['email'] . "</td>";
            echo "<td width='25%'>" . $row['password'] . "</td>";
            echo "</tr>";
        }
    }
    echo '</table>';
}
?>
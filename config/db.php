<?php
// session_start();

$db_host = "localhost:3307";
$db_user = "root";
$db_pass = ""; 
$db_name   = "inventory_db";

$conn =  mysqli_connect($db_host, $db_user, $db_pass, $db_name);


// if ($conn){
//     echo "You are connected";

// }
// else{
//     echo"You are not connected";
// }
// ?>
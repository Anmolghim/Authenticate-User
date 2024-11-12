<?php
$servername = "localhost";
$username ="root";
$password = "";
$dbname = "database1";

$conn = new mysqli($servername,$username,$password,$dbname);

if($conn->connect_error){
die ("connection error" .mysqli_error($conn));
}
else {
    // echo "connection sucessfull";
}
?>
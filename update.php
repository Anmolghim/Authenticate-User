<?php
 include "dbconnect.php";

 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $update = $_POST['password'];

    $stmt = $conn->prepare("UPDATE TABLE `login_table` SET password= ? where password = ");
    $stmt->bind_param("s",$update);
    $stmt->execute();
    $result =$stmt->get_result();

    if($result->num_rows > 0){
        echo "<script> alert('successfully updated the password')<script>";
    }else{
        echo "Could not update the password";
    }
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css 
    ">
    <link rel="stylesheet" href="style.css">
    <style>
     
    </style>
</head>
<body>
<div id="container">
<form action="update.php" method="POST">
  <h2>Enter the new password</h2>
  <div class="input-icons">
  <i class="fa fa-key icon"></i>
                <input class="input-field" type="text" name="password" placeholder="Enter the new password " required>
            </div>
            <button type="submit">Create New Password</button>
            </div>
  </form>
</body>
</html>
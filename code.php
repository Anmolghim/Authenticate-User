<?php
 include "dbconnect.php";
 $succced = 0;
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $otp = $_POST['otp'];

$stmt = $conn->prepare("SELECT * FROM `otp` WHERE otp= ?");
$stmt->bind_param("i",$otp);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
echo "<script> alert('otp matched sucessfully') 
</script>";

}
else {
    echo "please check the email and verify the opt";
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
<form action="code.php" method="POST">
   
            <h2>Check your email and Enter Otp</h2>
            <div class="input-icons">
                          <i class="fa fa-envelope icon"></i>
                          <input class="input-field" type="number" name="otp" placeholder="Enter the Otp " required>
                      </div>
                      <button type="submit">Submit Otp</button>
                      </div>
       
 

  
  
  
  </form>
</body>
</html>
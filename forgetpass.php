<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


include 'dbconnect.php';
include 'function.php';
date_default_timezone_set("Asia/Kathmandu");

$succed = isset($_SESSION['succed']) ? $_SESSION['succed'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && $succed == 0) {
        $email_pass = $_POST['email'];

        // $_SESSION['email'] = $email_pass; //store email in session

        $stmt = $conn->prepare("SELECT * FROM `login_table` WHERE email = ?");
        $stmt->bind_param("s", $email_pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // generate OTP
            $otp = rand(100000, 999999);
            // send otp
            $mail_status = Send_otp($email_pass, $otp);

            if ($mail_status) {
                $stmt = $conn->prepare("INSERT INTO `otp` (otp_id, date) VALUES (?, current_timestamp())");
                $stmt->bind_param("i", $otp);
                $stmt->execute();

                if ($stmt->insert_id) {
                    echo "<script>alert('OTP inserted into the database');</script>";
                } else {
                    echo "OTP insertion failed";
                }

                if ($mail_status) {
                    echo "<script>alert('OTP has been sent to your email');</script>";
                    $_SESSION['succed'] = 1;
                    $succed = 1;
                } else {
                    echo "Failed to send OTP";
                }
            }
        } else {
            echo "<script>alert('Email does not exist');</script>";
        }
    } elseif (isset($_POST['otp']) && $succed == 1) {
        $otp = $_POST['otp'];

        $stmt = $conn->prepare("SELECT * FROM `otp` WHERE otp_id = ?");
        $stmt->bind_param("i", $otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('OTP matched successfully');</script>";
            $_SESSION['succed'] = 2;
            $succed = 2;
        } else {
            echo "Please check the email and verify the OTP";
        }
    } elseif (isset($_POST['password']) && isset($_POST['email']) && $succed == 2) {
        $update = $_POST['password'];
        $email_pass = $_POST['email'];

        $stmt = $conn->prepare("UPDATE login_table SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $update, $email_pass);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Successfully updated the password');</script>";
            $_SESSION['succed'] = 3;
            $succed = 3;
        } else {
            echo "Could noooot update the password";
        }
    } elseif ($succed == 3) {
        echo "Successfully updated the password";
        session_unset();
        session_destroy();
    }
    $conn->close();
}
$email_pass= "";
// $succed = 2;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<style>
    #container {
        height: 250px;
        width: 600px;
        background-color: white;
        border-radius: 20px;
    }
    h1 {
        padding: 10px;
    }
</style>
<body>
<div id="container">
    <form action="" method="POST">
        <?php 
        // echo  "a" .$succed; 
        // echo  "<br> b" .$email_pass;
        if ($succed == 0) {
            echo '
                <h1>Email to update Password</h1>
                <div class="input-icons">
                    <i class="fa fa-envelope icon"></i>
                    <input class="input-field" type="email" name="email" placeholder="Email" required>
                </div>
                <button type="submit">Submit Email</button>
            ';
        } elseif ($succed == 1) {
            echo '
                <h2>Check your email and enter OTP</h2>
                <div class="input-icons">
                    <i class="fa fa-key icon"></i>
                    <input class="input-field" type="number" name="otp" placeholder="Enter the OTP" required>
                </div>
                <button type="submit">Submit OTP</button>
            ';
        } elseif ($succed == 2) {
            echo '
                <h2>Enter the new password</h2>
                <div class="input-icons">
                    <i class="fa fa-key icon"></i>
                    <input class="input-field" type="password" name="password" placeholder="Enter the new password" required>
                    <input type = "email" name ="email" >
                </div>
                <button type="submit">Create New Password</button>
            ';
        }
        ?>
    </form>
</div>
</body>
</html>

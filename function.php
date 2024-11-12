<?php

error_reporting(E_ALL);

// Enable display of errors
ini_set('display_errors', 1);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function Send_otp($email,$otp){
// include "PHPMailer\PHPMailer\PHPMailer";
// include "PHPMailer\PHPMailer\Exception";

// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMPT.php';

// require ('phpmailer/vendor/phpmailer/src');
// require ('PHPMailer/src/Exception.php');
// require ('PHPMailer/src/SMTP.php');
// require ('PHPMailer/src/POP3.php');

require 'phpmailer\vendor\autoload.php';

$message_body= "your otp code is here <br/><br/> <h1>" .$otp . "</h1>";

$mail = new PHPMailer();

$mail->SMTPDebug=0;
$mail->isSMTP();
$mail->SMTPAuth= true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Host= 'smtp.gmail.com';
$mail->Port='465';

$mail->Username = 'anmolghimire286@gmail.com';
$mail->Password = 'ffoh uthn lxmo wjnj';

$mail->addReplyTo('anmolghimire286@gmail.com','Anmol Ghimire');
$mail->setFrom('anmolghimire286@gmail.com','Anmol Ghmire');
$mail->addAddress($email);
$mail->Subject= "OTP to login";
$mail->msgHTML($message_body);
$result = $mail->send();

if (!$result){
    echo "mail error 123 " .$mail->ErrorInfo;

}else {
    return true;
}
}

?>
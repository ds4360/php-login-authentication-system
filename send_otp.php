<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'smtp_config.php';


/*
|--------------------------------------------------------------------------
| Check OTP Session
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['otp']) ||
    !isset($_SESSION['otp_time']) ||
    !isset($_SESSION['reset_email'])
) {
    die("Invalid OTP request.");
}


/*
|--------------------------------------------------------------------------
| OTP Expiry - 5 Minutes
|--------------------------------------------------------------------------
*/

if (time() - $_SESSION['otp_time'] > 300) {

    unset($_SESSION['otp']);
    unset($_SESSION['otp_time']);

    die("OTP expired. Please request a new OTP.");
}


$otp = $_SESSION['otp'];
$email = $_SESSION['reset_email'];


$mail = new PHPMailer(true);

$mail->SMTPDebug = 0;


try {

    // SMTP
    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = $smtp_username;

    $mail->Password = $smtp_password;

    $mail->SMTPSecure =
        PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;


    // Sender
    $mail->setFrom(
        $smtp_username,
        'My Website'
    );


    // Receiver
    $mail->addAddress($email);


    // Email
    $mail->Subject = 'Password Reset OTP';

    $mail->Body =
        "Your OTP is: "
        . $otp
        . "\n\nThis OTP is valid for 5 minutes.";


    // Send
    $mail->send();


    // Go to OTP verification
    header("Location: verify_otp.php");
    exit();


} catch (Exception $e) {

    echo "Email could not be sent ❌";
    echo "<br><br>";
    echo htmlspecialchars($mail->ErrorInfo);

}

?>

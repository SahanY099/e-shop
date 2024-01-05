<?php

$app_email = 'replace with your email';
$app_password = 'replace with your password';



include "connection.php";

include "SMTP.php";
include "PHPMailer.php";
include "Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

// 
// 
// 
// 
//  Be sure not to show upper part of in the video
//  this file as it contains my email
// 
// 
// 

if (isset($_GET["email"])) {

    $email = $_GET["email"];

    $q = "SELECT * FROM `user` WHERE `email` = '$email'";
    $rs = Database::search($q);
    $n = $rs->num_rows;

    if ($n == 1) {
        $code = uniqid();
        Database::iud(
            "UPDATE `user`
            SET
                `verification_code` = '$code'
            WHERE
                `email` = '$email'"
        );

        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $app_email;
        $mail->Password = $app_password;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom($app_email, 'Reset Password');
        $mail->addReplyTo($app_email, 'Reset Password');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'eShop forgot password verification code';
        $bodyContent = '<h1 style="color: green;">Your verification code is ' . $code . '</h1>';
        $mail->Body = $bodyContent;

        if (!$mail->send()) {
            echo "Error while sending verification code";
        } else {
            echo "success";
        }

    } else {
        echo "Invalid email address";
    }
} else {
    echo "Please enter your email address in email field";
}

<?php

$app_email = 'replace with your email';
$app_password = 'replace with your password';



session_start();

include "connection.php";

include "SMTP.php";
include "PHPMailer.php";
include "Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST["email"])) {
    $email = $_POST["email"];
    $category_name = $_POST["name"];

    if ($_SESSION["adminUser"]["email"] == $email) {
        $category_rs = Database::search(
            "SELECT *
            FROM `category`
            WHERE
                `cat_name` LIKE '%$category_name$'"
        );
        $category_num = $category_rs->num_rows;

        if ($category_num == 0) {
            $code = uniqid();

            Database::iud(
                "UPDATE `admin`
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
            $mail->setFrom($app_email, 'Admin Verification');
            $mail->addReplyTo($app_email, 'Admin Verification');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'eShop Admin Login Verification Code for Add new category';
            $bodyContent = '<h1 style="color: blue;">Your verification code is ' . $code . '</h1>';
            $mail->Body = $bodyContent;

            if (!$mail->send()) {
                echo "Error while sending verification code";
            } else {
                echo "success";
            }

        } else {
            echo "This category already exists";
        }

    } else {
        echo "Invalid user";
    }

} else {
    echo "Something is missing";
}

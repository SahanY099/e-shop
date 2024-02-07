<?php
// 
$admin_email = 'admin email';
// 
// 
// 
// 
// 
// 
// 
// 
// 
// 
// 
// 
// 
// 
// 
// 


session_start();
include "connection.php";

$msg_text = $_POST["text"];
$receiver = $_POST["email"];

$sender;

if (isset($_SESSION["user"])) {
    $sender = $_SESSION["user"]["email"];
} elseif (isset($_SESSION["adminUser"])) {
    $sender = $_SESSION["adminUser"]["email"];
}

$date = new DateTime();
$tz = new DateTimeZone("Asia/Colombo");
$date->setTimezone($tz);
$date = $date->format("Y-m-d H:i:s");

if (!empty($receiver)) {
    Database::iud(
        "INSERT INTO `chat`
            (`content`, `date_time`, `status`, `from`, `to`)
        VALUES
            ('$msg_text', '$date', '0', '$sender', '$receiver')"
    );

    echo "success-1";
} else {
    Database::iud(
        "INSERT INTO `chat`
            (`content`, `date_time`, `status`, `from`, `to`)
        VALUES
            ('$msg_text', '$date', '0', '$sender', '$admin_email')"
    );

    echo "success-2";
}

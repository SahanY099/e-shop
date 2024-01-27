<?php

session_start();

include "connection.php";

$sender = $_SESSION["user"]["email"];
$receiver = $_POST["receiverEmail"];
$msg = $_POST["msgText"];

$date = new DateTime();
$tz = new DateTimeZone("Asia/Colombo");
$date->setTimeZone($tz);
$date = $date->format("Y-m-d H:i:s");

Database::iud(
    "INSERT INTO `chat`
        (`content`, `date_time`, `status`, `from`, `to`)
    VALUES
        ('$msg', '$date', '0', '$sender', '$receiver')"
);

echo "success";

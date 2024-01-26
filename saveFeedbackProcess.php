<?php

session_start();

include "connection.php";

if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    $email = $user["email"];

    if (isset($_POST["feed"]) && isset($_POST["type"]) && isset($_POST["productId"])) {
        $feed = $_POST["feed"];
        $type = $_POST["type"];
        $product_id = $_POST["productId"];

        $date = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $date->setTimeZone($tz);
        $date = $date->format("Y-m-d H:i:s");

        Database::iud(
            "INSERT INTO `feedback`
                (`user_email`, `type`, `date`, `feed`, `product_id`)
            VALUES
                ('$email', '$type', '$date', '$feed', '$product_id')"
        );

        echo "success";

    } else {
        echo "Something went wrong. Please try again later";
    }

}

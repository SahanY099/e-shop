<?php

session_start();

include "connection.php";

if (isset($_SESSION["user"])) {

    if (isset($_POST["orderId"]) && isset($_POST["productId"]) && isset($_POST["email"]) && isset($_POST["amount"]) && isset($_POST["qty"])) {

        $order_id = $_POST["orderId"];
        $product_id = $_POST["productId"];
        $email = $_POST["email"];
        $amount = $_POST["amount"];
        $qty = $_POST["qty"];

        $product_rs = Database::search(
            "SELECT *
        FROM    `product`
        WHERE
            `id` = '$product_id'"
        );
        $product_data = $product_rs->fetch_assoc();

        $current_qty = $product_data["qty"];
        $new_qty = $current_qty - $qty;

        Database::iud(
            "UPDATE `product`
        SET
            `qty` = '$new_qty'
        WHERE
            `id` = '$product_id'"
        );

        $date = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $date->setTimezone($tz);
        $date = $date->format('Y-m-d H:i:s');

        Database::iud(
            "INSERT INTO `invoice`
            (`order_id`, `product_id`, `user_email`, `total`, `qty`, `date`, `status`)
        VALUES
            ('$order_id', '$product_id', '$email', '$amount', '$qty', '$date', '0')"
        );

        echo "success";

    } else {
        echo "Something went wrong. Please try again later";
    }

} else {
    echo "Please login first";
}

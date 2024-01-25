<?php

session_start();

include "connection.php";

if (isset($_SESSION["user"])) {
    if (isset($_GET["cartId"]) & isset($_GET["qty"])) {
        $qty = $_GET["qty"];
        $cart_id = $_GET["cartId"];

        $cart_rs = Database::search(
            "SELECT *
            FROM `cart`
            WHERE
                `cart_id`='$cart_id'"
        );
        $cart_data = $cart_rs->fetch_assoc();

        $product_id = $cart_data["product_id"];
        $product_rs = Database::search(
            "SELECT *
            FROM `product`
            WHERE
                `id` = '$product_id'"
        );
        $product_data = $product_rs->fetch_assoc();
        $product_qty = $product_data["qty"];

        $current_qty = $cart_data["qty"];

        if ($product_qty >= $qty) {
            Database::iud(
                "UPDATE `cart`
                SET
                    `qty` = '$qty'
                WHERE
                    `cart_id` = $cart_id"
            );

            echo "updated";

        } else {
            echo "Not enough items in stock";
        }

    } else {
        echo "Something went wrong. Please try again later";
    }

} else {
    echo "Please login first";
}

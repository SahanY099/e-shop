<?php

session_start();
include "connection.php";

if (isset($_SESSION["user"])) {
    $email = $_SESSION["user"]["email"];

    if (isset($_GET["productId"])) {
        $product_id = $_GET["productId"];

        $cart_rs = Database::search(
            "SELECT *
            FROM `cart`
            WHERE
                `product_id`='$product_id' AND
                `user_email`='$email'"
        );
        $cart_num = $cart_rs->num_rows;

        $product_rs = Database::search(
            "SELECT *
            FROM `product`
            WHERE
                `id` = '$product_id'"
        );
        $product_data = $product_rs->fetch_assoc();
        $product_qty = $product_data["qty"];

        if ($cart_num == 1) {
            $cart_data = $cart_rs->fetch_assoc();
            $cart_id = $cart_data["cart_id"];

            $current_qty = $cart_data["qty"];
            $new_qty = (int) $current_qty + 1;

            if ($product_qty >= $new_qty) {
                Database::iud(
                    "UPDATE `cart`
                    SET
                        `qty` = $new_qty
                    WHERE
                        `cart_id` = $cart_id"
                );

                echo "Cart updated";
            } else {
                echo "Not enough items in stock";
            }

        } else {
            Database::iud(
                "INSERT INTO `cart`
                    (`user_email`, `product_id`, `qty`)
                VALUES
                    ('$email', '$product_id', 1)"
            );

            echo "Product added to the cart";
        }
    } else {
        echo "Something went wrong. Please try again later";
    }
} else {
    echo "Please login or signup first";
}

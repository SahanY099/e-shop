<?php

session_start();
include "connection.php";

$email = $_SESSION["user"]["email"];
$product_id = $_GET['productId'];

$product_rs = Database::search(
    "SELECT *
    FROM `product`
    WHERE
        `id` = '$product_id' AND
        `user_email` = '$email'"
);
$product_num = $product_rs->num_rows;

if ($product_num == 1) {
    $product_data = $product_rs->fetch_assoc();
    $_SESSION["product"] = $product_data;

    echo "success";
} else {
    echo "Something went wrong. Please try again later";
}

<?php

include "connection.php";

$product_id = $_GET['productId'];

$product_rs = Database::search("SELECT * FROM `product` WHERE `id` = '" . $product_id . "'");
$product_num = $product_rs->num_rows;

if ($product_num == 1) {
    $product_id = $product_rs->fetch_assoc();
    $status = $product_id['status_status_id'];

    if ($status == 1) {
        Database::iud(
            "UPDATE `product`
            SET
                `status_status_id` = '2'
            WHERE
                `id` = '" . $product_id['id'] . "'"
        );

        echo "deactivated";
    } elseif ($status == 2) {
        Database::iud(
            "UPDATE `product`
            SET
                `status_status_id` = '1'
            WHERE
                `id` = '" . $product_id['id'] . "'"
        );

        echo "activated";
    }

} else {
    echo "Something went wrong. Please try again later";
}

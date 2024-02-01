<?php

include "connection.php";

if (isset($_GET["productId"])) {
    $product_id = $_GET["productId"];

    $product_rs = Database::search(
        "SELECT *
        FROM `product`
        WHERE
            `id` = '$product_id'"
    );
    $product_num = $product_rs->num_rows;

    if ($product_num == 1) {
        $product_data = $product_rs->fetch_assoc();

        if ($product_data["status_status_id"] == 1) {
            Database::iud(
                "UPDATE `product`
                SET
                    `status_status_id` = '2'
                WHERE
                    `id` = '$product_id'"
            );

            echo $product_data['title'] . " blocked";

        } elseif ($product_data["status_status_id"] == 2) {
            Database::iud(
                "UPDATE `product`
                SET
                    `status_status_id` = '1'
                WHERE
                    `id` = '$product_id'"
            );
            echo $product_data['title'] . " unblocked";

        }

    } else {
        echo "Cannot find the product. Please try again later.";
    }

} else {
    echo "Something went wrong.";
}

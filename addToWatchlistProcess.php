<?php

session_start();
include "connection.php";

if (isset($_SESSION["user"])) {
    $email = $_SESSION["user"]["email"];

    if (isset($_GET["productId"])) {
        $product_id = $_GET["productId"];

        $watchlist_rs = Database::search(
            "SELECT * 
            FROM `watchlist`
            WHERE
                `user_email` = '$email' AND
                `product_id` = '$product_id'"
        );
        $watchlist_num = $watchlist_rs->num_rows;

        if ($watchlist_num == 0) {
            Database::iud(
                "INSERT INTO `watchlist` (`user_email`, `product_id`)
                VALUES
                    ('$email', '$product_id')"
            );
            echo "added";

        } else if ($watchlist_num == 1) {
            $watchlist_data = $watchlist_rs->fetch_assoc();
            $list_id = $watchlist_data["w_id"];

            Database::iud(
                "DELETE FROM `watchlist`
                WHERE
                    `w_id` = '$list_id'"
            );
            echo "removed";
        }

    } else {
        echo "Something went wrong. Please try again later.";
    }
} else {
    echo "Please login first";
}

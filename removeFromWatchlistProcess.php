<?php

session_start();
include "connection.php";

if (isset($_SESSION["user"])) {
    $email = $_SESSION["user"]["email"];

    if (isset($_GET["watchlistId"])) {
        $watchlist_id = $_GET["watchlistId"];

        $watchlist_rs = Database::search(
            "SELECT * 
            FROM `watchlist`
            WHERE
                `w_id` = '$watchlist_id'"
        );
        $watchlist_num = $watchlist_rs->num_rows;

        if ($watchlist_num == 0) {
            echo "Something went wrong. Please try again later";
        } else {
            $watchlist_data = $watchlist_rs->fetch_assoc();

            Database::iud(
                "INSERT INTO `recent`
                    (`product_id`, `user_email`)
                VALUES
                    ('" . $watchlist_data["product_id"] . "', '" . $email . "')"
            );

            Database::iud(
                "DELETE FROM `watchlist`
                WHERE
                    `w_id` = '$watchlist_id'"
            );

            echo "success";
        }
    }
} else {
    echo "Please login first";
}

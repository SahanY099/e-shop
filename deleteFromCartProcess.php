<?php

include "connection.php";

if (isset($_GET["cartId"])) {
    $cart_id = $_GET["cartId"];

    Database::iud(
        "DELETE
        FROM `cart`
        WHERE
            `cart_id` = '" . $cart_id . "'"
    );

    echo "removed";

} else {
    echo "Something went wrong. Please try again later";
}

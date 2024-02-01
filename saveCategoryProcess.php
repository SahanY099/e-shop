<?php

include "connection.php";

if (isset($_POST["email"]) && isset($_POST["categoryName"]) && isset($_POST["verificationCode"])) {
    $email = $_POST["email"];
    $category_name = $_POST["categoryName"];
    $verification_code = $_POST["verificationCode"];

    $admin_rs = Database::search(
        "SELECT *
        FROM `admin`
        WHERE
            `email` = '$email'"
    );
    $admin_num = $admin_rs->num_rows;

    if ($admin_num == 1) {
        Database::iud(
            "INSERT INTO `category` (`cat_name`)
            VALUES
                ('$category_name')"
        );
        echo "success";

    } else {
        echo "Invalid user";
    }

} else {
    echo "Something is missing";
}

<?php

session_start();

include "connection.php";

if (isset($_POST["verificationCode"])) {
    $verification_code = $_POST["verificationCode"];

    $admin_rs = Database::search(
        "SELECT *
        FROM `admin`
        WHERE
            `verification_code` = '$verification_code'"
    );
    $admin_num = $admin_rs->num_rows;

    if ($admin_num == 1) {
        $admin_data = $admin_rs->fetch_assoc();
        $_SESSION["adminUser"] = $admin_data;

        echo "success";

    } else {
        echo "Invalid verification code";
    }

} else {
    echo "Please enter the verification code";
}
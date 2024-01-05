<?php

include "connection.php";

$email = $_POST['email'];
$newPassword = $_POST['newPassword'];
$retypeNewPassword = $_POST['retypeNewPassword'];
$verificationCode = $_POST['verificationCode'];

if ($newPassword != $retypeNewPassword) {
    echo "Password does not match";
} else {
    $q = "SELECT *
        FROM `user`
        WHERE
            `email` = '$email' AND
            `verification_code` = '$verificationCode'";
    $rs = Database::search($q);
    $num = $rs->num_rows;

    if ($num == 1) {
        Database::iud(
            "UPDATE `user`
            SET
                `password` = '$newPassword'
            WHERE
                `email` = '$email'"
        );
        echo "success";
    } else {
        echo "Invalid email address or verification code";
    }
}

<?php

session_start();
include "connection.php";

$email = $_SESSION["user"]['email'];

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$mobile = $_POST['mobile'];
$line1 = $_POST['line1'];
$line2 = $_POST['line2'];
$province = $_POST['province'];
$district = $_POST['district'];
$city = $_POST['city'];
$postalCode = $_POST['postalCode'];

$user_rs = Database::search("SELECT * FROM `user` WHERE `email` = '" . $email . "'");

if ($user_rs->num_rows == 1) {
    Database::iud(
        "UPDATE `user`
        SET `fname` = '" . $fname . "',
            `lname` = '" . $lname . "',
            `mobile` = '" . $mobile . "'
        WHERE
            `email` = '" . $email . "'"
    );

    $address_rs = Database::search("SELECT *
        FROM `user_has_address`
        WHERE
            `user_email` = '" . $email . "'"
    );

    if ($address_rs->num_rows == 1) {
        Database::iud("UPDATE `user_has_address`
            SET
                `line1` = '" . $line1 . "',
                `line2` = '" . $line2 . "',
                `city_city_id` = '" . $city . "',
                `postal_code` = '" . $postalCode . "'
            WHERE
                `user_email` = '" . $email . "'");
    } else {
        Database::iud(
            "INSERT INTO `user_has_address`
                (`user_email`, `line1`, `line2`, `city_city_id`, `postal_code`)
            VALUES
                (
                    '" . $email . "',
                    '" . $line1 . "',
                    '" . $line2 . "',
                    '" . $city . "',
                    '" . $postalCode . "'
                )"
        );
    }

    if (sizeof($_FILES) == 1) {
        $image = $_FILES['profileImage'];

        $image_extension = $image['type'];

        $allowed_image_extensions = array("image/jpeg", "image/png", "image/svg+xml");

        if (in_array($image_extension, $allowed_image_extensions)) {
            $new_image_extension;

            if ($image_extension == "image/jpeg") {
                $new_image_extension = ".jpeg";
            } elseif ($image_extension == "image/png") {
                $new_image_extension = ".png";
            } elseif ($image_extension == "image/svg+xml") {
                $new_image_extension = ".svg";
            }

            $file_name = "resources//profile_images//" . $fname . "_" . uniqid() . $new_image_extension;
            move_uploaded_file($image['tmp_name'], $file_name);

            $profile_image_rs = Database::search(
                "SELECT *
                FROM `profile_img`
                WHERE
                    `user_email` = '" . $email . "'"
            );

            if ($profile_image_rs->num_rows == 1) {
                Database::iud(
                    "UPDATE `profile_img`
                    SET `path` = '" . $file_name . "'
                    WHERE `user_email` = '" . $email . "'"
                );

                echo "Updated";
            } else {
                Database::iud(
                    "INSERT INTO `profile_img`
                        (`user_email`, `path`)
                    VALUES
                        ('" . $email . "', '" . $file_name . "')"
                );

                echo "Saved";
            }
        }
    } elseif (empty($_FILES)) {
        if ($address_rs->num_rows == 0) {
            echo "no-image-selected";
        } else {
            echo "Updated";
        }
    } else {
        echo "You must select only 1 profile image";
    }
} else {
    echo "Invalid user";
}

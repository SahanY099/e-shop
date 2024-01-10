<?php

session_start();
include "connection.php";

if (isset($_SESSION["product"])) {
    $product_id = $_SESSION["product"]["id"];

    $title = $_POST['title'];
    $qty = $_POST['qty'];
    $deliveryWithinColombo = $_POST['deliveryWithinColombo'];
    $deliveryOutOfColombo = $_POST['deliveryOutOfColombo'];
    $description = $_POST['description'];

    Database::iud(
        "UPDATE `product`
        SET
            `title` = '$title',
            `qty` = $qty,
            `delivery_fee_colombo` = $deliveryWithinColombo,
            `delivery_fee_other` = $deliveryOutOfColombo,
            `description` = '$description'
        WHERE
            `id` = $product_id"
    );

    echo "product-updated-successfully";

    $length = sizeof($_FILES);

    if ($length <= 3 && $length > 0) {
        $allowed_image_extensions = array("image/jpeg", "image/png", "image/svg+xml");

        $img_rs = Database::search(
            "SELECT *
            FROM `product_img`
            WHERE
                `product_id` = '$product_id'"
        );
        $img_num = $img_rs->num_rows;

        for ($i = 0; $i < $img_num; $i++) {
            $img_data = $img_rs->fetch_assoc();

            unlink($img_data['img_path']);

            Database::iud(
                "DELETE FROM `product_img`
                WHERE
                    `product_id` = '$product_id'"
            );
        }

        for ($x = 0; $x < $length; $x++) {
            if (isset($_FILES["image-" . $x])) {
                $image_file = $_FILES["image-" . $x];
                $image_extension = $image_file['type'];

                if (in_array($image_extension, $allowed_image_extensions)) {
                    $new_image_extension;

                    if ($image_extension == "image/jpeg") {
                        $new_image_extension = ".jpeg";
                    } elseif ($image_extension == "image/png") {
                        $new_image_extension = ".png";
                    } elseif ($image_extension == "image/svg+xml") {
                        $new_image_extension = ".svg";
                    }

                    $file_name = "resources//product_images//" . $title . "_" . $x . "_" . uniqid() . $new_image_extension;
                    move_uploaded_file($image_file['tmp_name'], $file_name);

                    Database::iud(
                        "INSERT INTO `product_img` (`img_path`, `product_id`)
                        VALUES
                            ('$file_name', '$product_id')"

                    );
                } else {
                    echo "Invalid image type " . $image_extension . " ";
                }
            }
        }

    } else {
        echo "Invalid image count";
    }
}

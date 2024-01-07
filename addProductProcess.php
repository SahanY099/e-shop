<?php

session_start();
include "connection.php";

$category = $_POST['category'];
$brand = $_POST['brand'];
$model = $_POST['model'];
$title = $_POST['title'];
$condition = $_POST['condition'];
$color = $_POST['color'];
$description = $_POST['description'];
$qty = $_POST['qty'];
$cost = $_POST['cost'];
$deliveryWithinColombo = $_POST['deliveryWithinColombo'];
$deliveryOutOfColombo = $_POST['deliveryOutOfColombo'];

if (empty($_SESSION["user"])) {
    echo "Please login first";
} elseif (empty($category)) {
    echo "Please select a category";
} elseif (empty($brand)) {
    echo "Please enter a brand";
} elseif (empty($model)) {
    echo "Please enter a model";
} elseif (empty($title)) {
    echo "Please enter a title";
} elseif (strlen($title) > 100) {
    echo "Title must contain lower than 100 characters";
} elseif (empty($condition)) {
    echo "Please select a condition";
} elseif (empty($color)) {
    echo "Please enter a color";
} elseif (empty($description)) {
    echo "Please enter a description";
} elseif (strlen($description) > 500) {
    echo "Description must contain lower than 500 characters";
} elseif (empty($qty)) {
    echo "Please enter a quantity";
} elseif (empty($cost)) {
    echo "Please enter a cost";
} elseif (empty($deliveryWithinColombo)) {
    echo "Please enter a cost for delivery within Colombo";
} elseif (empty($deliveryOutOfColombo)) {
    echo "Please enter a cost for delivery out of Colombo";
} else {

    $model_has_brand_rs = Database::search(
        "SELECT *
        FROM `model_has_brand`
        WHERE
            `brand_brand_id` = '" . $brand . "' AND
            `model_model_id` = '" . $model . "'"
    );

    $model_has_brand_id;

    if ($model_has_brand_rs->num_rows > 0) {
        $model_has_brand_data = $model_has_brand_rs->fetch_assoc();
        $model_has_brand_id = $model_has_brand_data['id'];

    } else {
        Database::iud(
            "INSERT INTO `model_has_brand`
                (`model_model_id`, `brand_brand_id`)
            VALUES
                ($model, $brand)"
        );

        $model_has_brand_id = Database::$connection->insert_id;
    }

    $date = new DateTime();
    $tz = new DateTimeZone("Asia/Colombo");
    $date->setTimezone($tz);
    $date = $date->format('Y-m-d H:i:s');

    $status = 1;

    Database::iud(
        "INSERT INTO `product` (
            `price`, `qty`, `description`, `title`, `datetime_added`,
            `delivery_fee_colombo`, `delivery_fee_other`, `category_cat_id`,
            `model_has_brand_id`, `condition_condition_id`, `status_status_id`, `user_email`
            )
        VALUES
            (
                '$cost', '$qty', '$description', '$title', '$date',
                '$deliveryWithinColombo', '$deliveryOutOfColombo', '$category',
                '$model_has_brand_id', '$condition', '$status', '" . $_SESSION["user"]["email"] . "'
            )
    "
    );

    $product_id = Database::$connection->insert_id;

    $length = sizeof($_FILES);

    if ($length <= 3 && $length > 0) {
        $allowed_image_extensions = array("image/jpeg", "image/png", "image/svg+xml");

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

        echo "success";

    } else {
        echo "Invalid image count";
    }

}

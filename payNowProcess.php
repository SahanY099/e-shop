<?php

session_start();

include "connection.php";

if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    $email = $user["email"];

    if (isset($_GET["productId"]) && isset($_GET["qty"])) {
        $qty = $_GET["qty"];
        $product_id = $_GET["productId"];

        $array;

        $order_id = uniqid();

        $product_rs = Database::search(
            "SELECT *
            FROM `product`
            WHERE
                `id` = '$product_id'"
        );
        $product_data = $product_rs->fetch_assoc();

        $city_rs = Database::search(
            "SELECT *
            FROM `user_has_address`
            WHERE `user_email` = '$email'"
        );
        $city_num = $city_rs->num_rows;

        if ($city_num == 1) {
            $city_data = $city_rs->fetch_assoc();

            $city_id = $city_data["city_city_id"];
            $address = $city_data["line1"] . " " . $city_data["line2"];

            $city_rs = Database::search(
                "SELECT *
                FROM `city`
                WHERE
                    `city_id` = '$city_id'"
            );
            $city_data = $city_rs->fetch_assoc();

            $district_id = $city_data["district_district_id"];
            $delivery_fee = "0";

            // number should be according to the database index of colombo
            if ($district_id == "5") {
                $delivery_fee = $product_data["delivery_fee_colombo"];
            } else {
                $delivery_fee = $product_data["delivery_fee_other"];
            }

            $item = $product_data["title"];
            $amount = ($product_data["price"] * (int) $qty) + $delivery_fee;
            $fname = $_SESSION["user"]["fname"];
            $lname = $_SESSION["user"]["lname"];
            $mobile = $_SESSION["user"]["mobile"];
            $user_address = $address;
            $city = $city_data["city_name"];

            $merchant_id = "1224612";
            $merchant_secret = "MzU4OTg2Mjc0MTEzNDMyNjU5NzMxNTc3NjI1NjMyMjYzOTk4OTI3NQ==";
            $currency = "LKR";

            $hash = strtoupper(
                md5(
                    $merchant_id .
                    $order_id .
                    $amount .
                    $currency .
                    strtoupper(md5($merchant_secret))
                )
            );

            $array["order_id"] = $order_id;
            $array["item"] = $item;
            $array["amount"] = $amount;
            $array["fname"] = $fname;
            $array["lname"] = $lname;
            $array["mobile"] = $mobile;
            $array["address"] = $user_address;
            $array["city"] = $city;
            $array["merchant_id"] = $merchant_id;
            $array["currency"] = $currency;
            $array["hash"] = $hash;
            $array["email"] = $email;

            echo json_encode($array);

        } else {
            echo "2";
        }

    }
} else {
    echo "1";
}

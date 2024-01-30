<!DOCTYPE html>
<html lang="en_US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Panel | eShop</title>

    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css" />

    <link rel="icon" href="resources/logo.svg" />
</head>

<body style="background-color: #74EBD5;background-image: linear-gradient(90deg,#74EBD5 0%,#9FACE6 100%);">

    <div class="container-fluid">
        <div class="row">

            <?php

            session_start();

            include "connection.php";

            if (isset($_SESSION["adminUser"])) {
                $admin_user = $_SESSION["adminUser"];

                ?>

            <div class="col-12 col-lg-2">
                <div class="row">
                    <div class="col-12 align-items-start bg-dark vh-100">
                        <div class="row g-1 text-center">

                            <div class="col-12 mt-5">
                                <h4 class="text-white">
                                    <?php echo $admin_user["fname"] . " " . $admin_user["lname"]; ?>
                                </h4>
                                <hr class="border border-1 border-white" />
                            </div>
                            <div class="nav flex-column nav-pills me-3 mt-3" role="tablist" aria-orientation="vertical">
                                <nav class="nav flex-column">
                                    <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                                    <a class="nav-link" href="manageUsers.php">Manage Users</a>
                                    <a class="nav-link" href="manageProduct.php">Manage Products</a>
                                </nav>
                            </div>
                            <div class="col-12 mt-5">
                                <hr class="border border-1 border-white" />
                                <h4 class="text-white fw-bold">Selling History</h4>
                                <hr class="border border-1 border-white" />
                            </div>
                            <div class="col-12 mt-3 d-grid">
                                <label class="form-label fs-6 fw-bold text-white">From Date</label>
                                <input type="date" class="form-control" />
                                <label class="form-label fs-6 fw-bold text-white mt-2">To Date</label>
                                <input type="date" class="form-control" />
                                <a href="#" class="btn btn-primary mt-2">Search</a>
                                <hr class="border border-1 border-white" />
                                <label class="form-label fs-6 fw-bold text-white">Daily Sellings</label>
                                <hr class="border border-1 border-white" />
                                <hr class="border border-1 border-white" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-10">
                <div class="row">

                    <div class="text-white fw-bold mb-1 mt-3">
                        <h2 class="fw-bold">Dashboard</h2>
                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12">
                        <div class="row g-1">

                            <div class="col-6 col-lg-4 px-1 shadow">
                                <div class="row g-1">
                                    <div class="col-12 bg-primary text-white text-center rounded"
                                        style="height: 100px;">

                                        <?php

                                            $tz = new DateTimeZone("Asia/Colombo");
                                            $today = (new DateTime())->setTimezone($tz)->format("Y-m-d");
                                            $this_month = (new DateTime())->setTimezone($tz)->format("m");
                                            $this_year = (new DateTime())->setTimezone($tz)->format("Y");

                                            $today_earnings = 0;
                                            $monthly_earnings = 0;
                                            $today_sellings = 0;
                                            $monthly_sellings = 0;
                                            $total_sellings = 0;

                                            $invoice_rs = Database::search(
                                                "SELECT *
                                                FROM `invoice`"
                                            );
                                            $invoice_num = $invoice_rs->num_rows;

                                            for ($i = 0; $i < $invoice_num; $i++) {
                                                $invoice_data = $invoice_rs->fetch_assoc();

                                                $total_sellings = $total_sellings + $invoice_data["qty"]; // total qty
                                        
                                                $invoice_date = $invoice_data["date"];
                                                $split_date = explode(" ", $invoice_date); // separate the date from time
                                                $sold_date = $split_date["0"];

                                                $split_month = explode("-", $sold_date); // separate date as year, moth & day
                                                $sold_year = $split_month["0"];
                                                $sold_month = $split_month["1"];

                                                if ($sold_year == $this_year) {
                                                    if ($sold_month == $this_month) {
                                                        $monthly_earnings = $monthly_earnings + $invoice_data["total"];
                                                        $monthly_sellings = $monthly_sellings + $invoice_data["qty"];

                                                        if ($sold_date == $today) {
                                                            $today_earnings = $today_earnings + $invoice_data["total"];
                                                            $today_sellings = $today_sellings + $invoice_data["qty"];
                                                        }
                                                    }


                                                }

                                            }

                                            ?>

                                        <br />
                                        <span class="fs-4 fw-bold">Daily Earnings</span>
                                        <br />
                                        <span class="fs-5">
                                            <?php echo $today_earnings; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-4 px-1">
                                <div class="row g-1">
                                    <div class="col-12 bg-white text-black text-center rounded" style="height: 100px;">
                                        <br />
                                        <span class="fs-4 fw-bold">Monthly Earnings</span>
                                        <br />

                                        <span class="fs-5">
                                            <?php echo $monthly_earnings; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-4 px-1">
                                <div class="row g-1">
                                    <div class="col-12 bg-dark text-white text-center rounded" style="height: 100px;">
                                        <br />
                                        <span class="fs-4 fw-bold">Today Sellings</span>
                                        <br />
                                        <span class="fs-5">
                                            <?php echo $today_sellings; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-4 px-1">
                                <div class="row g-1">
                                    <div class="col-12 bg-secondary text-white text-center rounded"
                                        style="height: 100px;">
                                        <br />
                                        <span class="fs-4 fw-bold">Monthly Sellings</span>
                                        <br />
                                        <span class="fs-5">
                                            <?php echo $monthly_sellings; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-4 px-1">
                                <div class="row g-1">
                                    <div class="col-12 bg-success text-white text-center rounded"
                                        style="height: 100px;">
                                        <br />
                                        <span class="fs-4 fw-bold">Total Sellings</span>
                                        <br />
                                        <span class="fs-5">
                                            <?php echo $total_sellings; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-4 px-1 shadow">
                                <div class="row g-1">
                                    <div class="col-12 bg-danger text-white text-center rounded" style="height: 100px;">
                                        <br />
                                        <span class="fs-4 fw-bold">Total Engagements</span>
                                        <br />
                                        <span class="fs-5">
                                            <?php

                                                $user_rs = Database::search(
                                                    "SELECT *
                                                FROM `user`"
                                                );
                                                $user_num = $user_rs->num_rows;

                                                echo $user_num;

                                                ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12">
                        <hr />
                    </div>

                    <div class="col-12 bg-dark">
                        <div class="row">
                            <div class="col-12 col-lg-2 text-center my-3">
                                <label class="form-label fs-4 fw-bold text-white">Total Active Time</label>
                            </div>
                            <div class="col-12 col-lg-10 text-center my-3">

                                <label class="form-label fs-4 fw-bold text-warning">
                                    <?php

                                        $start_date = new DateTime("2023-12-15 19:00:00");

                                        $today_date = new DateTime();
                                        $tz = new DateTimeZone("Asia/Colombo");
                                        $today_date->setTimezone($tz);
                                        $end_date = new DateTime($today_date->format("Y-m-d H:i:s"));

                                        $difference = $end_date->diff($start_date);

                                        echo
                                            $difference->format("%Y") . " Years " . $difference->format("%m") . " Months " .
                                            $difference->format("%H") . " Hours " . $difference->format("%d") . " Days " .
                                            $difference->format("%i") . " Minutes " . $difference->format("%s") . " Seconds";

                                        ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="offset-1 col-10 col-lg-4 my-3 rounded bg-body">
                        <div class="row g-1">
                            <div class="col-12 text-center">
                                <label class="form-label fs-4 fw-bold text-decoration-underline">
                                    Mostly Sold Item
                                </label>
                            </div>

                            <?php

                                $frequency_rs = Database::search(
                                    "SELECT
                                    `product_id`, COUNT(`product_id`) as `value_occurrence`
                                FROM `invoice`
                                WHERE
                                    `date` LIKE '%$today%'
                                GROUP BY `product_id`
                                ORDER BY `value_occurrence` DESC LIMIT 1"
                                );
                                $frequency_num = $frequency_rs->num_rows;

                                if ($frequency_num > 0) {
                                    $frequency_data = $frequency_rs->fetch_assoc();

                                    $product_rs = Database::search(
                                        "SELECT *
                                    FROM `product`
                                    INNER JOIN `product_img` ON
                                        product.id = product_img.product_id
                                    INNER JOIN `user` ON
                                        product.user_email = user.email
                                    INNER JOIN `profile_img` ON
                                        user.email = profile_img.user_email
                                    WHERE
                                        `id` = '" . $frequency_data["product_id"] . "'"
                                    );
                                    $product_data = $product_rs->fetch_assoc();

                                    $qty_rs = Database::search(
                                        "SELECT SUM(`qty`) AS `qty_total`
                                        FROM `invoice`
                                        WHERE
                                            `product_id` = '" . $frequency_data["product_id"] . "' AND
                                            `date` LIKE '%$today%'"
                                    );
                                    $qty_data = $qty_rs->fetch_assoc();

                                    ?>

                            <div class="col-12 text-center shadow">
                                <img src="<?php echo $product_data["img_path"]; ?>" class="img-fluid rounded-top"
                                    style="height: 250px;" />
                            </div>
                            <div class="col-12 text-center my-3">
                                <span class="fs-5 fw-bold">
                                    <?php echo $product_data["title"]; ?>
                                </span>
                                <br />
                                <span class="fs-6">
                                    <?php echo $qty_data["qty_total"]; ?>
                                </span>
                                <br />
                                <span class="fs-6">
                                    <?php echo $qty_data["qty_total"] * $product_data["price"]; ?>
                                </span>
                            </div>

                            <?php
                                } else {
                                    ?>

                            <div class="col-12 text-center shadow">
                                <img src="resources/empty.svg" class="img-fluid rounded-top" style="height: 250px;" />
                            </div>
                            <div class="col-12 text-center my-3">
                                <span class="fs-5 fw-bold">-----</span>
                                <br />
                                <span class="fs-6">--- items</span>
                                <br />
                                <span class="fs-6">Rs. ----- .00</span>
                            </div>

                            <?php
                                }
                                ?>

                            <div class="col-12">
                                <div class="first-place"></div>
                            </div>
                        </div>
                    </div>

                    <div class="offset-1 col-10 col-lg-4 my-3 rounded bg-body">
                        <div class="row g-1">

                            <?php
                                if ($frequency_num > 0) {
                                    ?>

                            <div class="col-12 text-center">
                                <label class="form-label fs-4 fw-bold text-decoration-underline">
                                    Most Famous Seller
                                </label>
                            </div>
                            <div class="col-12 text-center shadow">
                                <img src="<?php echo $product_data["path"] ?>" class=" img-fluid rounded-top"
                                    style="height: 250px;" />
                            </div>
                            <div class="col-12 text-center my-3">
                                <span class="fs-5 fw-bold">
                                    <?php echo $product_data["fname"] . " " . $product_data["lname"]; ?>
                                </span>
                                <br />
                                <span class="fs-6">
                                    <?php echo $product_data["email"] ?>
                                </span>
                                <br />
                                <span class="fs-6">
                                    <?php echo $product_data["mobile"] ?>
                                </span>
                            </div>

                            <?php
                                } else {
                                    ?>

                            <div class="col-12 text-center">
                                <label class="form-label fs-4 fw-bold text-decoration-underline">
                                    Most Famous Seller
                                </label>
                            </div>
                            <div class="col-12 text-center shadow">
                                <img src="resources/new_user.svg" class="img-fluid rounded-top"
                                    style="height: 250px;" />
                            </div>
                            <div class="col-12 text-center my-3">
                                <span class="fs-5 fw-bold">----- -----</span>
                                <br />
                                <span class="fs-6">-----</span>
                                <br />
                                <span class="fs-6">----------</span>
                            </div>

                            <?php
                                }
                                ?>

                            <div class="col-12">
                                <div class="first-place"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php
            } else {
                ?>

            <h1>
                You are not authorized to access this page
            </h1>

            <?php
            }

            ?>

        </div>
    </div>

    <script src="bootstrap.bundle.js"></script>
    <script src="script.js"></script>
</body>

</html>
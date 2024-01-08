<?php
session_start();
include "connection.php";

if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    $email = $user["email"];
    $pageNo;
    ?>

    <!DOCTYPE html>

    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>My Products | eShop</title>

        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" />
        <link rel="stylesheet" href="style.css" />

        <link rel="icon" href="resource/logo.svg" />

    </head>

    <body style="background-color: #E9EBEE;">

        <div class="container-fluid">
            <div class="row">

                <!-- header -->
                <div class="col-12 bg-primary">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="row">
                                <div class="col-12 col-lg-4 mt-1 mb-1 text-center">
                                    <?php

                                    $profile_img_rs = Database::search(
                                        "SELECT *
                                        FROM `profile_img`
                                        WHERE
                                            `user_email` = '$email'"
                                    );
                                    $profile_img_num = $profile_img_rs->num_rows;

                                    if ($profile_img_num == 1) {
                                        $profile_img_data = $profile_img_rs->fetch_assoc();
                                        $profile_img_path = $profile_img_data["path"];

                                    } else {
                                        $profile_img_path = "resources/new_user.svg";
                                    }

                                    ?>

                                    <img width="90px" height="90px" alt="Profile image" class="rounded-circle"
                                        src="<?php echo $profile_img_path; ?>" />

                                </div>
                                <div class="col-12 col-lg-8">
                                    <div class="row text-center text-lg-start">
                                        <div class="col-12 mt-0 mt-lg-4">
                                            <span class="text-white fw-bold">
                                                <?php echo $user['fname'] . " " . $user['lname']; ?>
                                            </span>
                                        </div>
                                        <div class="col-12">
                                            <span class="text-black-50 fw-bold"><?php echo $user['email']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-8">
                            <div class="row">
                                <div class="col-12 col-lg-10 mt-2 my-lg-4">
                                    <h1 class="offset-4 offset-lg-2 text-white fw-bold">My Products</h1>
                                </div>
                                <div class="col-12 col-lg-2 mx-2 mb-2 my-lg-4 mx-lg-0 d-grid">
                                    <button class="btn btn-warning fw-bold"
                                        onclick="window.location.href='addProduct.php'">Add Product</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- header -->

                <!-- body -->
                <div class="col-12">
                    <div class="row">
                        <!-- filter -->
                        <div class="col-11 col-lg-2 mx-3 my-3 border border-primary rounded">
                            <div class="row">
                                <div class="col-12 mt-3 fs-5">
                                    <div class="row">

                                        <div class="col-12">
                                            <label class="form-label fw-bold fs-3">Sort Products</label>
                                        </div>
                                        <div class="col-11">
                                            <div class="row">
                                                <div class="col-10">
                                                    <input type="text" placeholder="Search..." class="form-control" value=""
                                                        id="search" autocomplete="off" />
                                                </div>
                                                <div class="col-1 p-1">
                                                    <label class="form-label"><i class="bi bi-search fs-5"></i></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-bold">Active Time</label>
                                        </div>
                                        <div class="col-12">
                                            <hr style="width: 80%;" />
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="r1" id="new-to-old">
                                                <label class="form-check-label" for="new-to-old">
                                                    Newest to oldest
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="r1" id="old-to-new">
                                                <label class="form-check-label" for="old-to-new">
                                                    Oldest to newest
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label class="form-label fw-bold">By quantity</label>
                                        </div>
                                        <div class="col-12">
                                            <hr style="width: 80%;" />
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="r2" id="high-to-low">
                                                <label class="form-check-label" for="high-to-low">
                                                    High to low
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="r2" id="low-to-high">
                                                <label class="form-check-label" for="low-to-high">
                                                    Low to high
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label class="form-label fw-bold">By condition</label>
                                        </div>
                                        <div class="col-12">
                                            <hr style="width: 80%;" />
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="r3" id="brand-new">
                                                <label class="form-check-label" for="brand-new">
                                                    Brand New
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="r3" id="used">
                                                <label class="form-check-label" for="used">
                                                    Used
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12 text-center mt-3 mb-3">
                                            <div class="row g-2">
                                                <div class="col-12 col-lg-6 d-grid">
                                                    <button class="btn btn-success fw-bold"
                                                        onclick="sortMyProducts(<?php $pageNo ?>);">Sort</button>
                                                </div>
                                                <div class="col-12 col-lg-6 d-grid">
                                                    <button class="btn btn-primary fw-bold"
                                                        onclick="clearSort();">Clear</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- filter -->

                        <!-- product -->
                        <div class="col-12 col-lg-9 mt-3 mb-3 bg-white">
                            <div class="row" id="sort">

                                <div class="offset-1 col-10 text-center">
                                    <div class="row justify-content-center">

                                        <?php

                                        if (isset($_GET["page"])) {
                                            $pageNo = $_GET["page"];
                                        } else {
                                            $pageNo = 1;
                                        }

                                        $product_rs = Database::search(
                                            "SELECT *
                                            FROM `product`
                                            WHERE
                                                `user_email` = '$email'"
                                        );
                                        $product_num = $product_rs->num_rows;

                                        $results_per_page = 5;
                                        $number_of_pages = ceil($product_num / $results_per_page);

                                        $page_results_offset = ($pageNo - 1) * $results_per_page;
                                        $selected_rs = Database::search(
                                            "SELECT *
                                            FROM `product`
                                            WHERE
                                                `user_email` = '$email'
                                            LIMIT $results_per_page OFFSET $page_results_offset"
                                        );

                                        $selected_num = $selected_rs->num_rows;
                                        for ($i = 0; $i < $selected_num; $i++) {
                                            $product_data = $selected_rs->fetch_assoc();
                                            ?>

                                            <!-- card -->
                                            <div class="card mb-3 mt-3 col-12 col-lg-6">
                                                <div class="row">
                                                    <div class="col-md-4 mt-4">

                                                        <?php

                                                        $product_img_rs = Database::search(
                                                            "SELECT *
                                                        FROM `product_img`
                                                        WHERE
                                                            `product_id` = '$product_data[id]' LIMIT 1"
                                                        );
                                                        $product_img_data = $product_img_rs->fetch_assoc();

                                                        ?>

                                                        <img src="<?php echo $product_img_data["img_path"]; ?>"
                                                            alt="Product image" class="img-fluid rounded-start" />
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="card-body">
                                                            <h5 class="card-title fw-bold">
                                                                <?php echo $product_data["title"]; ?>
                                                            </h5>
                                                            <span class="card-text fw-bold text-primary">
                                                                Rs.<?php echo $product_data["price"]; ?>.00
                                                            </span>
                                                            <br />
                                                            <span class="card-text fw-bold text-success">
                                                                <?php echo $product_data["qty"]; ?> Items left
                                                            </span>
                                                            <div class="form-check form-switch">
                                                                <input id="toggle-<?php echo $product_data["id"]; ?>"
                                                                    onchange="changeStatus(<?php echo $product_data['id']; ?>);"
                                                                    role="switch" class="form-check-input" type="checkbox" <?php if ($product_data["status_status_id"] == 2) {
                                                                        echo "checked";
                                                                    }
                                                                    ?> />
                                                                <label class="form-check-label fw-bold text-info"
                                                                    for="toggle-<?php echo $product_data["id"]; ?>">

                                                                    <?php

                                                                    if ($product_data["status_status_id"] == 1) {
                                                                        echo "Deactivate your product";
                                                                    } else {
                                                                        echo "Activate your product";
                                                                    }

                                                                    ?>

                                                                </label>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="row g-1">
                                                                        <div class="col-12 d-grid">
                                                                            <button class="btn btn-success fw-bold">
                                                                                Update
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- card -->

                                            <?php
                                        }

                                        ?>


                                    </div>
                                </div>

                                <div class="offset-2 offset-lg-3 col-8 col-lg-6 text-center mb-3">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination pagination-lg justify-content-center">
                                            <li class="page-item">
                                                <a class="page-link" href="
                                                <?php

                                                if ($pageNo <= 1) {
                                                    echo "#";
                                                } else {
                                                    echo "?page=" . ($pageNo - 1);
                                                }

                                                ?>
                                                " aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>

                                            <?php

                                            for ($i = 1; $i < $number_of_pages + 1; $i++) {
                                                if ($i == $pageNo) {
                                                    ?>

                                                    <li class="page-item active">
                                                        <a class="page-link" href="<?php echo "?page=" . $i; ?>">
                                                            <?php echo $i; ?>
                                                        </a>
                                                    </li>

                                                    <?php
                                                } else {
                                                    ?>

                                                    <li class="page-item">
                                                        <a class="page-link" href="<?php echo "?page=" . $i; ?>">
                                                            <?php echo $i; ?>
                                                        </a>
                                                    </li>

                                                    <?php
                                                }
                                            }

                                            ?>


                                            <li class="page-item">
                                                <a class="page-link" href="
                                                <?php

                                                if ($pageNo >= $number_of_pages) {
                                                    echo "#";
                                                } else {
                                                    echo "?page=" . ($pageNo + 1);
                                                }

                                                ?>
                                                " aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>

                            </div>
                        </div>
                        <!-- product -->

                    </div>
                </div>
                <!-- body -->

                <?php include "footer.php"; ?>

            </div>
        </div>

        <script src="script.js"></script>
    </body>

    </html>


    <?php
} else {
    ?>

    <script>
        window.location = "home.php"
    </script>

    <?php
}

?>


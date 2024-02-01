<!DOCTYPE html>
<html lang="en_US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Products | Admins | eShop</title>

    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css" />

    <link rel="icon" href="resources/logo.svg" />

</head>

<body style="background-color: #74EBD5;background-image: linear-gradient(90deg,#74EBD5 0%,#9FACE6 100%);">

    <div class="container-fluid">
        <div class="row">

            <div class="col-12 bg-light text-center">
                <label class="form-label text-primary fw-bold fs-1">Manage All Products</label>
            </div>

            <?php

            session_start();

            include "connection.php";

            if (isset($_SESSION["adminUser"])) {
                $admin_user = $_SESSION["adminUser"];

                ?>

                <div class="col-12 mt-3">
                    <div class="row">
                        <div class="offset-0 offset-lg-3 col-12 col-lg-6 mb-3">
                            <div class="row">
                                <div class="col-9">
                                    <input type="text" class="form-control" />
                                </div>
                                <div class="col-3 d-grid">
                                    <button class="btn btn-warning">Search Product</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3 mb-3">
                    <div class="row">
                        <div class="col-2 col-lg-1 bg-primary py-2 text-end">
                            <span class="fs-4 fw-bold text-white">#</span>
                        </div>
                        <div class="col-2 d-none d-lg-block bg-light py-2">
                            <span class="fs-4 fw-bold">Product Image</span>
                        </div>
                        <div class="col-4 col-lg-2 bg-primary py-2">
                            <span class="fs-4 fw-bold text-white">Title</span>
                        </div>
                        <div class="col-4 col-lg-2 d-lg-block bg-light py-2">
                            <span class="fs-4 fw-bold">Price</span>
                        </div>
                        <div class="col-2 d-none d-lg-block bg-primary py-2">
                            <span class="fs-4 fw-bold text-white">Quantity</span>
                        </div>
                        <div class="col-2 d-none d-lg-block bg-light py-2">
                            <span class="fs-4 fw-bold">Registered Date</span>
                        </div>
                        <div class="col-2 col-lg-1 bg-white"></div>
                    </div>
                </div>

                <?php
                $query = "SELECT * FROM `product`";
                $pageNo;

                if (isset($_GET["page"])) {
                    $pageNo = $_GET["page"];
                } else {
                    $pageNo = 1;
                }

                $product_rs = Database::search($query);
                $product_num = $product_rs->num_rows;

                $results_per_page = 20;
                $number_of_pages = ceil($product_num / $results_per_page);

                $page_results_offset = ($pageNo - 1) * $results_per_page;
                $selected_rs = Database::search(
                    "SELECT *
                    FROM `product`
                    LIMIT $results_per_page
                    OFFSET $page_results_offset"
                );

                $selected_num = $selected_rs->num_rows;
                for ($i = 0; $i < $selected_num; $i++) {
                    $product_data = $selected_rs->fetch_assoc();
                    ?>

                    <div class="col-12 mt-3 mb-3">
                        <div class="row">
                            <div class="col-2 col-lg-1 bg-primary py-2 text-end">
                                <span class="fs-4 fw-bold text-white">
                                    <?php echo $product_data["id"]; ?>
                                </span>
                            </div>
                            <div class="col-2 d-none d-lg-block bg-light py-2"
                                onclick="viewProductModal('<?php echo $product_data['id']; ?>');">

                                <?php

                                $product_image_rs = Database::search(
                                    "SELECT *
                                    FROM `product_img`
                                    WHERE
                                        `product_id` = '" . $product_data["id"] . "'"
                                );
                                $product_img_num = $product_image_rs->num_rows;

                                if ($product_img_num > 0) {
                                    $product_img_path = ($product_image_rs->fetch_assoc())["img_path"];
                                } else {
                                    $product_img_path = "resources/empty.svg";
                                }

                                ?>

                                <img src="<?php echo $product_img_path; ?>" style="height: 40px;margin-left: 80px;"
                                    alt="Product image" />
                            </div>
                            <div class="col-4 col-lg-2 bg-primary py-2">
                                <span class="fs-5 fw-bold text-white">
                                    <?php echo $product_data["title"]; ?>
                                </span>
                            </div>
                            <div class="col-4 col-lg-2 d-lg-block bg-light py-2">
                                <span class="fs-4 fw-bold">
                                    <?php echo $product_data["price"]; ?>
                                </span>
                            </div>
                            <div class="col-2 d-none d-lg-block bg-primary py-2">
                                <span class="fs-4 fw-bold text-white">
                                    <?php echo $product_data["qty"]; ?>
                                </span>
                            </div>
                            <div class="col-2 d-none d-lg-block bg-light py-2">
                                <span class="fs-5 fw-bold">
                                    <?php echo $product_data["datetime_added"]; ?>
                                </span>
                            </div>
                            <div class="col-2 col-lg-1 bg-white py-2 d-grid">

                                <?php
                                if ($product_data["status_status_id"] == 1) {
                                    ?>

                                    <button class="btn btn-danger" id="product-block-<?php $product_data['id']; ?>"
                                        onclick="blockProduct('<?php echo $product_data['id']; ?>');">Block</button>

                                    <?php
                                } else {
                                    ?>

                                    <button class="btn btn-success" id="product-block-<?php $product_data['id']; ?>"
                                        onclick="blockProduct('<?php echo $product_data['id']; ?>');">Unblock</button>
                                    <?php

                                }
                                ?>

                            </div>
                        </div>
                    </div>

                    <!-- modal 01 -->
                    <div class="modal" tabindex="-1" id="product-modal-<?php echo $product_data['id']; ?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold text-success">Apple iPhone 12</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="offset-4 col-4">
                                        <img src="<?php echo $product_img_path; ?>" class="img-fluid" alt="Product image"
                                            style="height: 150px;" />
                                    </div>
                                    <div class="col-12">
                                        <span class="fs-5 fw-bold">Price :</span>&nbsp;
                                        <span class="fs-5">
                                            <?php echo $product_data["price"]; ?>
                                        </span>
                                        <br />

                                        <span class="fs-5 fw-bold">Quantity :</span>&nbsp;
                                        <span class="fs-5">
                                            <?php echo $product_data["qty"]; ?> Products left</span>
                                        <br />

                                        <span class="fs-5 fw-bold">Seller :</span>&nbsp;
                                        <span class="fs-5">
                                            <?php
                                            $seller_rs = Database::search(
                                                "SELECT *
                                                FROM `user`
                                                WHERE
                                                    `email` = '" . $product_data["user_email"] . "'"
                                            );
                                            $seller_data = $seller_rs->fetch_assoc();

                                            echo $seller_data["fname"] . " " . $seller_data["lname"];

                                            ?>
                                        </span>
                                        <br />

                                        <span class="fs-5 fw-bold">Description :</span>&nbsp;
                                        <span class="fs-5">
                                            <?php echo $product_data["description"]; ?>
                                        </span>
                                        <br />

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal 01 -->

                    <?php
                }
                ?>

                <!--  -->
                <div class="offset-2 offset-lg-3 col-8 col-lg-6 text-center mb-3">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination pagination-lg justify-content-center">
                            <li class="page-item">
                                <a class="page-link" href="<?php

                                if ($pageNo <= 1) {
                                    echo "#";
                                } else {
                                    echo "?page=" . ($pageNo - 1);
                                }

                                ?>" aria-label="Previous">
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
                                <a class="page-link" href="<?php

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
                <!--  -->

                <hr />

                <div class="col-12 text-center">
                    <h3 class="text-black-50 fw-bold">Manage Categories</h3>
                </div>

                <div class="col-12 mb-3">
                    <div class="row gap-1 justify-content-center">

                        <?php

                        $category_rs = Database::search("SELECT * FROM `category`");
                        $category_num = $category_rs->num_rows;

                        for ($i = 0; $i < $category_num; $i++) {
                            $category_data = $category_rs->fetch_assoc();
                            ?>

                            <div class="col-12 col-lg-3 border border-danger rounded" style="height: 50px;">
                                <div class="row">
                                    <div class="col-8 mt-2 mb-2">
                                        <label class="form-label fw-bold fs-5">
                                            <?php echo $category_data["cat_name"]; ?>
                                        </label>
                                    </div>
                                    <div class="col-4 border-start border-secondary text-center mt-2 mb-2">
                                        <label class="form-label fs-4"><i class="bi bi-trash3-fill text-danger"></i></label>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }

                        ?>



                        <div class="col-12 col-lg-3 border border-success rounded" style="height: 50px;"
                            onclick="addNewCategory();">
                            <div class="row">
                                <div class="col-8 mt-2 mb-2">
                                    <label class="form-label fw-bold fs-5">Add new Category</label>
                                </div>
                                <div class="col-4 border-start border-secondary text-center mt-2 mb-2">
                                    <label class="form-label fs-4">
                                        <i class="bi bi-plus-square-fill text-success"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- modal 2 -->
                <div class="modal" tabindex="-1" id="add-category-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <label class="form-label">New Category Name : </label>
                                    <input type="text" class="form-control" id="category-name" />
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="form-label">Enter Your Email : </label>
                                    <input type="text" class="form-control" id="email" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="verifyCategory();">
                                    Save New Category
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal 2 -->
                <!-- modal 3 -->
                <div class="modal" tabindex="-1" id="add-category-verification-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Verification</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12 mt-3 mb-3">
                                    <label class="form-label">Enter Your Verification Code : </label>
                                    <input type="text" class="form-control" id="verification-code" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="saveCategory();">Verify &
                                    Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal 3 -->

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

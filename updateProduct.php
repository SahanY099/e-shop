<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Update Product | eShop</title>
    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="style.css" />

    <link rel="icon" href="resources/logo.svg" />

</head>

<body>

    <div class="container-fluid">
        <div class="row gy-3">

            <?php

            include "header.php";

            if (isset($_SESSION["user"])) {
                if (isset($_SESSION["product"])) {

                    include "connection.php";

                    $product = $_SESSION["product"];

                    ?>

                    <div class="col-12">
                        <div class="row">

                            <div class="col-12 text-center">
                                <h2 class="h2 text-primary fw-bold">Update Product</h2>
                            </div>

                            <div class="col-12">
                                <div class="row">

                                    <div class="col-12 col-lg-4 border-end border-success">
                                        <div class="row">

                                            <div class="col-12">
                                                <label class="form-label fw-bold" style="font-size: 20px;">
                                                    Product Category
                                                </label>
                                            </div>

                                            <div class="col-12">
                                                <select class="form-select text-center" disabled>

                                                    <?php

                                                    $category_rs = Database::search(
                                                        "SELECT *
                                                        FROM `category`
                                                        WHERE
                                                            `cat_id` = '" . $product['category_cat_id'] . "'"
                                                    );
                                                    $category_data = $category_rs->fetch_assoc();
                                                    ?>

                                                    <option>
                                                        <?php echo $category_data['cat_name']; ?>
                                                    </option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4 border-end border-success">
                                        <div class="row">

                                            <div class="col-12">
                                                <label class="form-label fw-bold" style="font-size: 20px;">
                                                    Product Brand
                                                </label>
                                            </div>

                                            <div class="col-12">
                                                <select class="form-select text-center" disabled>

                                                    <?php
                                                    $brand_rs = Database::search(
                                                        "SELECT *
                                                        FROM `brand`
                                                        WHERE
                                                            `brand_id` IN (
                                                                SELECT `brand_brand_id`
                                                                FROM `model_has_brand`
                                                                WHERE
                                                                    `id` = '" . $product['model_has_brand_id'] . "')"
                                                    );
                                                    $brand_data = $brand_rs->fetch_assoc();

                                                    ?>

                                                    <option>
                                                        <?php echo $brand_data['brand_name']; ?>
                                                    </option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4 border-end border-success">
                                        <div class="row">

                                            <div class="col-12">
                                                <label class="form-label fw-bold" style="font-size: 20px;">
                                                    Product Model
                                                </label>
                                            </div>

                                            <div class="col-12">
                                                <select class="form-select text-center" disabled>

                                                    <?php

                                                    $model_rs = Database::search(
                                                        "SELECT *
                                                        FROM `model`
                                                        WHERE
                                                            `model_id` IN (
                                                                SELECT `model_model_id`
                                                                FROM `model_has_brand`
                                                                WHERE
                                                                    `id` = '" . $product['model_has_brand_id'] . "')"
                                                    );
                                                    $model_data = $model_rs->fetch_assoc();

                                                    ?>

                                                    <option><?php echo $model_data['model_name']; ?></option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="border-success" />
                                    </div>

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label fw-bold" style="font-size: 20px;">
                                                    Product Title
                                                </label>
                                            </div>
                                            <div class="offset-0 offset-lg-2 col-12 col-lg-8">
                                                <input type="text" class="form-control" id="title"
                                                    value="<?php echo $product["title"]; ?>" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="border-success" />
                                    </div>

                                    <div class="col-12">
                                        <div class="row">

                                            <div class="col-12 col-lg-4 border-end border-success">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold" style="font-size: 20px;">
                                                            Product Condition
                                                        </label>
                                                    </div>
                                                    <div class="col-12">

                                                        <?php

                                                        if ($product["condition_condition_id"] == 1) {
                                                            ?>

                                                            <div class="form-check form-check-inline mx-5">
                                                                <input type="radio" id="b" name="c" checked disabled
                                                                    class="form-check-input" />
                                                                <label class="form-check-label fw-bold" for="b">
                                                                    Brand new
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input type="radio" id="u" name="c" disabled
                                                                    class="form-check-input" />
                                                                <label class="form-check-label fw-bold" for="u">
                                                                    Used
                                                                </label>
                                                            </div>

                                                            <?php
                                                        } else {
                                                            ?>

                                                            <div class="form-check form-check-inline mx-5">
                                                                <input type="radio" id="b" name="c" disabled
                                                                    class="form-check-input" />
                                                                <label class="form-check-label fw-bold" for="b">
                                                                    Brand new
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input type="radio" id="u" name="c" disabled checked
                                                                    class="form-check-input" />
                                                                <label class="form-check-label fw-bold" for="u">
                                                                    Used
                                                                </label>
                                                            </div>

                                                            <?php
                                                        }

                                                        ?>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-4 border-end border-success">
                                                <div class="row">

                                                    <div class="col-12">
                                                        <label class="form-label fw-bold" style="font-size: 20px;">
                                                            Product Colour
                                                        </label>
                                                    </div>

                                                    <div class="col-12">
                                                        <select class="form-select" disabled>

                                                            <?php

                                                            $color_rs = Database::search(
                                                                "SELECT *
                                                                FROM `color`
                                                                INNER JOIN  `product_has_color`
                                                                    ON color.clr_id = product_has_color.color_clr_id
                                                                WHERE
                                                                    `product_id` = '" . $product['id'] . "'"
                                                            );
                                                            $color_data = $color_rs->fetch_assoc();

                                                            ?>
                                                            <option>
                                                                <?php echo $color_data['clr_name']; ?>
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="input-group mt-2 mb-2">
                                                            <input disabled type="text" class="form-control"
                                                                placeholder="Add new Colour" />
                                                            <button class="btn btn-outline-primary" type="button"
                                                                id="button-addon2" disabled>+ Add</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-4">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold" style="font-size: 20px;">
                                                            Product Quantity
                                                        </label>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="number" class="form-control" min="0" id="qty"
                                                            value="<?php echo $product["qty"]; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="border-success" />
                                    </div>

                                    <div class="col-12">
                                        <div class="row">

                                            <div class="col-6 border-end border-success">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold" style="font-size: 20px;">
                                                            Cost Per Item
                                                        </label>
                                                    </div>
                                                    <div class="offset-0 offset-lg-2 col-12 col-lg-8">
                                                        <div class="input-group mb-2 mt-2">
                                                            <span class="input-group-text">Rs.</span>
                                                            <input type="text" class="form-control" disabled
                                                                value="<?php echo $product["price"]; ?>" />
                                                            <span class="input-group-text">.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold" style="font-size: 20px;">
                                                            Approved Payment Methods
                                                        </label>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="offset-0 offset-lg-2 col-2 pm pm1"></div>
                                                            <div class="col-2 pm pm2"></div>
                                                            <div class="col-2 pm pm3"></div>
                                                            <div class="col-2 pm pm4"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="border-success" />
                                    </div>

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label fw-bold" style="font-size: 20px;">
                                                    Delivery Cost
                                                </label>
                                            </div>
                                            <div class="col-12 col-lg-6 border-end border-success">
                                                <div class="row">
                                                    <div class="col-12 offset-lg-1 col-lg-3">
                                                        <label class="form-label">Delivery cost Within Colombo</label>
                                                    </div>
                                                    <div class="col-12 col-lg-8">
                                                        <div class="input-group mb-2 mt-2">
                                                            <span class="input-group-text">Rs.</span>
                                                            <input type="text" class="form-control"
                                                                value="<?php echo $product['delivery_fee_colombo'] ?>"
                                                                id="delivery-within-colombo" />
                                                            <span class="input-group-text">.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="row">
                                                    <div class="col-12 offset-lg-1 col-lg-3">
                                                        <label class="form-label">Delivery cost out of Colombo</label>
                                                    </div>
                                                    <div class="col-12 col-lg-8">
                                                        <div class="input-group mb-2 mt-2">
                                                            <span class="input-group-text">Rs.</span>
                                                            <input type="text" class="form-control"
                                                                value="<?php echo $product['delivery_fee_other'] ?>"
                                                                id="delivery-out-of-colombo" />
                                                            <span class="input-group-text">.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="border-success" />
                                    </div>

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label fw-bold" style="font-size: 20px;">
                                                    Product Description
                                                </label>
                                            </div>
                                            <div class="col-12">
                                                <textarea cols="30" rows="15" class="form-control"
                                                    id="description"><?php echo $product["description"]; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="border-success" />
                                    </div>

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label fw-bold" style="font-size: 20px;">Add Product
                                                    Images</label>
                                            </div>
                                            <div class="offset-lg-3 col-12 col-lg-6">

                                                <?php

                                                $img = array();

                                                $dummy_product_img_url = "resources/add-product-img.svg";

                                                $img[0] = $dummy_product_img_url;
                                                $img[1] = $dummy_product_img_url;
                                                $img[2] = $dummy_product_img_url;

                                                $product_img_rs = Database::search(
                                                    "SELECT *
                                                    FROM `product_img`
                                                    WHERE
                                                        `product_id` = '" . $product["id"] . "'"
                                                );
                                                $product_img_num = $product_img_rs->num_rows;

                                                for ($i = 0; $i < $product_img_num; $i++) {
                                                    $product_img_data = $product_img_rs->fetch_assoc();
                                                    $img[$i] = $product_img_data["img_path"];
                                                }

                                                ?>

                                                <div class="row">
                                                    <div class="col-4 border border-primary rounded">
                                                        <img alt="Dummy image" style="width: 250px;" class="img-fluid"
                                                            id="img-0" src="<?php echo $img[0] ?>" />
                                                    </div>
                                                    <div class="col-4 border border-primary rounded">
                                                        <img alt="Dummy image" style="width: 250px;" class="img-fluid"
                                                            id="img-1" src="<?php echo $img[1] ?>" />
                                                    </div>
                                                    <div class="col-4 border border-primary rounded">
                                                        <img alt="Dummy image" style="width: 250px;" class="img-fluid"
                                                            id="img-2" src="<?php echo $img[2] ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="offset-lg-3 col-12 col-lg-6 d-grid mt-3">
                                                <input type="file" class="d-none" id="image-uploader" multiple />
                                                <label for="image-uploader" class="col-12 btn btn-primary"
                                                    onclick="changeProductImages();">
                                                    Upload Images
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="border-success" />
                                    </div>

                                    <div class="offset-lg-4 col-12 col-lg-4 d-grid mt-3 mb-3">
                                        <button class="btn btn-dark" onclick="updateProduct();">Update Product</button>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <?php
                } else {
                    ?>

                    <script>
                        alert("Please select a product to update.");
                        window.location = "myProducts.php";
                    </script>

                    <?php
                }


            } else {
                ?>

                <script>
                    alert("You have to sign in to the system to access this function.");
                    window.location = "home.php";
                </script>

                <?php
            }

            ?>




            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="bootstrap.bundle.js"></script>
    <script src="script.js">
    </script>
</body>

</html>

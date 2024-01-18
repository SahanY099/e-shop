<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Samsung J7 | eShop</title>

    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css" />

    <link rel="icon" href="resources/logo.svg" />
</head>

<?php
include "connection.php";

if (isset($_GET["id"])) {
    $product_id = $_GET["id"];


    $product_rs = Database::search(
        "SELECT
            product.id, product.price, product.qty, product.description, product.title, product.datetime_added,
            product.delivery_fee_colombo, product.delivery_fee_other, product.status_status_id,
            product.user_email, product.condition_condition_id, product.model_has_brand_id,
            product.category_cat_id, model.model_name AS mname, brand.brand_name AS bname
        FROM `product`
        INNER JOIN `model_has_brand` ON model_has_brand.id=product.model_has_brand_id
        INNER JOIN `brand` ON brand.brand_id=model_has_brand.brand_brand_id
        INNER JOIN `model` ON model.model_id=model_has_brand.model_model_id
        WHERE product.id = '" . $product_id . "'
        "
    );
    $product_num = $product_rs->num_rows;

    if ($product_num == 1) {
        $product_details = $product_rs->fetch_assoc();

        ?>

        <body>

            <script>
                document.title = "<?php echo $product_details["title"]; ?> | eShop";
            </script>

            <div class="container-fluid">
                <div class="row">
                    <?php include "header.php"; ?>

                    <div class="col-12 mt-0 bg-white singleProduct">
                        <div class="row">
                            <div class="col-12" style="padding: 10px;">
                                <div class="row">

                                    <div class="col-12 col-lg-2 order-2 order-lg-1">
                                        <ul>

                                            <?php
                                            $image_rs = Database::search(
                                                "SELECT *
                                            FROM `product_img`
                                            WHERE product_id = '" . $product_id . "'"
                                            );
                                            $image_num = $image_rs->num_rows;
                                            $img = array();

                                            if ($image_num != 0) {
                                                for ($i = 0; $i < $image_num; $i++) {
                                                    $image_data = $image_rs->fetch_assoc();
                                                    $img[$i] = $image_data["img_path"];

                                                    ?>

                                                    <li class="d-flex flex-column justify-content-center align-items-center 
                                                    border border-1 border-secondary mb-1">
                                                        <img src="<?php echo $img[$i] ?>" class="img-thumbnail mt-1 mb-1"
                                                            alt="Product image" id="product-image-<?php echo $i; ?>"
                                                            onclick="loadMainImage(<?php echo $i; ?>);" />
                                                    </li>

                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <li class="d-flex flex-column justify-content-center align-items-center 
                                                border border-1 border-secondary mb-1">
                                                    <img src="resources/empty.svg" class="img-thumbnail mt-1 mb-1"
                                                        alt="Product image" />
                                                </li>
                                                <li class="d-flex flex-column justify-content-center align-items-center 
                                                border border-1 border-secondary mb-1">
                                                    <img src="resources/empty.svg" class="img-thumbnail mt-1 mb-1"
                                                        alt="Product image" />
                                                </li>
                                                <li class="d-flex flex-column justify-content-center align-items-center 
                                                border border-1 border-secondary mb-1">
                                                    <img src="resources/empty.svg" class="img-thumbnail mt-1 mb-1"
                                                        alt="Product image" />
                                                </li>
                                                <?php
                                            }

                                            ?>


                                        </ul>
                                    </div>

                                    <div class="col-lg-4 order-2 order-lg-1 d-none d-lg-block">
                                        <div class="row">
                                            <div class="col-12 align-items-center border border-1 
                                                border-secondary">
                                                <div class="mainImg" id="main-image-container"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6 order-3">
                                        <div class="row">
                                            <div class="col-12">

                                                <div class="row border-bottom border-dark">
                                                    <nav aria-label="breadcrumb">
                                                        <ol class="breadcrumb">
                                                            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                                                            <li class="breadcrumb-item active" aria-current="page">
                                                                Single Product View
                                                            </li>
                                                        </ol>
                                                    </nav>
                                                </div>

                                                <div class="row border-bottom border-dark">
                                                    <div class="col-12 my-2">
                                                        <span class="fs-4 fw-bold text-success">
                                                            <?php $product_details["title"] ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row border-bottom border-dark">
                                                    <div class="col-12 my-2">
                                                        <span class="badge">
                                                            <i class="bi bi-star-fill text-warning fs-5"></i>
                                                            <i class="bi bi-star-fill text-warning fs-5"></i>
                                                            <i class="bi bi-star-fill text-warning fs-5"></i>
                                                            <i class="bi bi-star-fill text-warning fs-5"></i>
                                                            <i class="bi bi-star-fill text-warning fs-5"></i>

                                                            &nbsp;&nbsp;&nbsp;

                                                            <label class="fs-5 text-dark fw-bold">
                                                                4.5 Stars | 39 Reviews and Ratings</label>
                                                        </span>
                                                    </div>
                                                </div>

                                                <?php

                                                $price = $product_details['price'];
                                                $adding_price = ($price / 100) * 10;
                                                $new_price = $price + $adding_price;
                                                $difference = $new_price - $price;

                                                ?>

                                                <div class="row border-bottom border-dark">
                                                    <div class="col-12 my-2">
                                                        <span class="fs-4 text-dark fw-bold">
                                                            Rs. <?php echo $price; ?>.00
                                                        </span>
                                                        &nbsp;&nbsp; | &nbsp;&nbsp;
                                                        <span class="fs-4 text-danger fw-bold text-decoration-line-through">
                                                            Rs. <?php echo $new_price; ?>.00
                                                        </span>
                                                        &nbsp;&nbsp; | &nbsp;&nbsp;
                                                        <span class="fs-4 fw-bold text-black-50">
                                                            Save Rs. <?php echo $difference; ?>.00 (10%)
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row border-bottom border-dark">
                                                    <div class="col-12 my-2">
                                                        <span class="fs-5 text-primary">
                                                            <b>Warranty : </b>6 Months Warranty
                                                        </span>
                                                        <br />
                                                        <span class="fs-5 text-primary">
                                                            <b>Return Policy : </b>1 Months Return Policy
                                                        </span>
                                                        <br />
                                                        <span class="fs-5 text-primary">
                                                            <b>In Stock : </b><?php echo $product_details["qty"] ?> Items
                                                            Available
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row border-bottom border-dark">
                                                    <div class="col-12 my-2">
                                                        <div class="row">

                                                            <?php

                                                            $seller_rs = Database::search(
                                                                "SELECT *
                                                                FROM `user`
                                                                WHERE
                                                                    `email` = '" . $product_details['user_email'] . "'"
                                                            );
                                                            $seller_details = $seller_rs->fetch_assoc();

                                                            ?>

                                                            <div
                                                                class="col-12 col-lg-6 border border-1 border-dark text-center">
                                                                <span class="fs-5 text-primary">
                                                                    <b>Seller : </b><?php echo $seller_details["fname"]; ?>
                                                                </span>
                                                            </div>
                                                            <div
                                                                class="col-12 col-lg-6 border border-1 border-dark text-center">
                                                                <span class="fs-5 text-primary">
                                                                    <b>Sold : </b>100 Items
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div
                                                                class="my-2 offset-lg-2 col-12 col-lg-8 border border-2 border-danger rounded">
                                                                <div class="row">
                                                                    <div
                                                                        class="col-3 col-lg-2 border-end border-2 border-danger">
                                                                        <img src="resources/price-tag.png" width="50" />
                                                                    </div>
                                                                    <div class="col-9 col-lg-10">
                                                                        <span class="fs-5 text-danger fw-bold">
                                                                            Stand a chance to get 5% discount by using VISA or
                                                                            MASTER
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-12 my-2">
                                                                <div class="row g-2">

                                                                    <div class="border border-1 border-secondary rounded overflow-hidden 
                                                        float-left mt-1 position-relative product-qty">
                                                                        <div class="col-12">
                                                                            <span>Quantity : </span>
                                                                            <input type="text"
                                                                                class="border-0 fs-5 fw-bold text-start"
                                                                                style="outline: none;" pattern="[0-9]" value="1"
                                                                                onkeyup="checkQuantityValue(<?php echo $product_details['qty'] ?>);"
                                                                                id="qty-input" />

                                                                            <div class="position-absolute qty-buttons">
                                                                                <div
                                                                                    class="justify-content-center d-flex flex-column 
                                                                            align-items-center border border-1 border-secondary qty-inc">
                                                                                    <i class="bi bi-caret-up-fill text-primary fs-5"
                                                                                        onclick="quantityIncrease(<?php echo $product_details['qty'] ?>);"></i>
                                                                                </div>
                                                                                <div class="justify-content-center d-flex flex-column align-items-center 
                                                                border border-1 border-secondary qty-dec">
                                                                                    <i class="bi bi-caret-down-fill text-primary fs-5"
                                                                                        onclick="quantityDecrease(<?php echo $product_details['qty'] ?>);"></i>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12 mt-5">
                                                                            <div class="row">
                                                                                <div class="col-4 d-grid">
                                                                                    <button class="btn btn-success">
                                                                                        Buy Now
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-4 d-grid">
                                                                                    <button class="btn btn-primary">
                                                                                        Add To Cart
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-4 d-grid">
                                                                                    <button class="btn btn-secondary">
                                                                                        <i
                                                                                            class="bi bi-heart-fill fs-4 text-danger"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12 bg-white">
                                <div class="row d-block me-0 mt-4 mb-3 border-bottom border-1 border-dark">
                                    <div class="col-12">
                                        <span class="fs-3 fw-bold">Related Items</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 bg-white">
                                <div class="row">

                                    <?php

                                    $related_rs = Database::search(
                                        "SELECT *
                                        FROM `product`
                                        WHERE
                                            `model_has_brand_id` = '" . $product_details['model_has_brand_id'] . "' LIMIT 5"
                                    );
                                    $related_num = $related_rs->num_rows;

                                    for ($i = 0; $i < $related_num; $i++) {
                                        $related_product_details = $related_rs->fetch_assoc();

                                        $img_rs = Database::search("SELECT *
                                            FROM `product_img`
                                            WHERE
                                                `product_id` = '" . $related_product_details['id'] . "'"
                                        );
                                        $img_data = $img_rs->fetch_assoc();
                                        ?>


                                        <div class="card" style="width: 18rem;">
                                            <img src="<?php echo $img_data["img_path"]; ?>" class="card-img-top"
                                                alt="Product image" />
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <?php echo $related_product_details["title"]; ?>
                                                </h5>
                                                <p class="card-text">
                                                    <?php echo $related_product_details["description"]; ?>
                                                </p>
                                                <a href="#" class="btn btn-primary">
                                                    Go somewhere
                                                </a>
                                            </div>
                                        </div>


                                        <?php
                                    }

                                    ?>


                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row d-block me-0 mt-4 mb-3 border-bottom border-1 border-dark border-end">
                                            <div class="col-12">
                                                <span class="fs-4 fw-bold">
                                                    Product Details
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row d-block me-0 mt-4 mb-3 border-bottom border-end border-1 border-dark">
                                            <div class="col-12">
                                                <span class="fs-4 fw-bold">
                                                    Feedbacks
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 col-lg-6 bg-white">
                                <div class="row">

                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-3">
                                                <label class="form-label fs-4 fw-bold">Brand : </label>
                                            </div>
                                            <div class="col-9">
                                                <label class="form-label fs-4">
                                                    <?php echo $product_details["bname"] ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-3">
                                                <label class="form-label fs-4 fw-bold">Model : </label>
                                            </div>
                                            <div class="col-9">
                                                <label class="form-label fs-4">
                                                    <?php echo $product_details["mname"] ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label fs-4 fw-bold">Description : </label>
                                            </div>
                                            <div class="col-12">
                                                <textarea cols="60" rows="10" class="form-control" readonly>
                                                                                                                                                                <?php echo $product_details["description"] ?>
                                                                                                                                                                </textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="row border border-1 border-dark rounded overflow-scroll me-0"
                                    style="height: 300px;">

                                    <div class="col-12 mt-1 mb-1 mx-1">
                                        <div class="row border border-1 border-dark rounded me-0">

                                            <div class="col-10 mt-1 mb-1 ms-0">Sahan Perera</div>
                                            <div class="col-2 mt-1 mb-1 me-0">

                                                <span class="badge bg-success">Positive</span>
                                            </div>

                                            <div class="col-12">
                                                <b>
                                                    good Product
                                                </b>
                                            </div>
                                            <div class="offset-6 col-6 text-end">
                                                <label class="form-label fs-6 text-black-50">2023-12-20 10:20:40</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <script src="bootstrap.bundle.js"></script>
            <script src="script.js"></script>
            <!-- <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script> -->
        </body>

        <?php
    } else {
        echo "Sorry for the inconvenience. Please try again later.";
    }
} else {
    echo "Something went wrong.";
}

?>

</html>

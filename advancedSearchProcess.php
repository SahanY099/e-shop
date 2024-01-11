<?php

include "connection.php";

$search_text = $_POST['text'];
$category = $_POST['category'];
$brand = $_POST['brand'];
$model = $_POST['model'];
$condition = $_POST['condition'];
$color = $_POST['color'];
$price_from = $_POST['priceFrom'];
$price_to = $_POST['priceTo'];
$sort = $_POST['sort'];
$page = $_POST['page'];

$query = "SELECT * FROM `product`";
$status = 0;

if (!empty($search_text)) {
    $query .= " WHERE `title` LIKE '%" . $search_text . "%'";
    $status = 1;
}

if ($category != 0 && $status == 0) {
    $query = " WHERE `category_cat_id` = '" . $category . "'";
    $status = 1;
} elseif ($category != 0 && $status != 0) {
    $query .= " AND `category_cat_id` = '" . $category . "'";
}


$model_has_brand_ids = [];

if ($brand != 0 && $model == 0) {
    $model_has_brand_rs = Database::search(
        "SELECT *
        FROM `model_has_brand`
        WHERE
            `brand_brand_id` = '" . $brand . "'"
    );
    $model_has_brand_num = $model_has_brand_rs->num_rows;


    for ($x = 0; $x < $model_has_brand_num; $x++) {
        $model_has_brand_data = $model_has_brand_rs->fetch_assoc();
        $model_has_brand_ids[$x] = $model_has_brand_data['id'];
    }

    if (!empty($model_has_brand_ids)) {
        if ($status == 0) {
            $query .= " WHERE `model_has_brand_id` IN (" . implode(", ", $model_has_brand_ids) . ")";
            $status = 1;
        } elseif ($status != 0) {
            $query .= " AND `model_has_brand_id` IN (" . implode(", ", $model_has_brand_ids) . ")";
        }
    }
}

if ($brand == 0 && $model != 0) {
    $model_has_brand_rs = Database::search(
        "SELECT *
        FROM `model_has_brand`
        WHERE
            `model_model_id` = '" . $model . "'"
    );
    $model_has_brand_num = $model_has_brand_rs->num_rows;

    for ($x = 0; $x < $model_has_brand_num; $x++) {
        $model_has_brand_data = $model_has_brand_rs->fetch_assoc();
        $model_has_brand_ids[$x] = $model_has_brand_data['id'];
    }
}

if ($brand != 0 && $model != 0) {
    $model_has_brand_rs = Database::search(
        "SELECT *
        FROM `model_has_brand`
        WHERE
            `brand_brand_id` = '" . $brand . "' AND
            `model_model_id` = '" . $model . "'"
    );
    $model_has_brand_num = $model_has_brand_rs->num_rows;

    for ($x = 0; $x < $model_has_brand_num; $x++) {
        $model_has_brand_data = $model_has_brand_rs->fetch_assoc();
        $model_has_brand_ids[$x] = $model_has_brand_data['id'];
    }
}

if (!empty($model_has_brand_ids)) {
    if ($status == 0) {
        $query .= " WHERE `model_has_brand_id` IN (" . implode(", ", $model_has_brand_ids) . ")";
        $status = 1;
    } elseif ($status != 0) {
        $query .= " AND `model_has_brand_id` IN (" . implode(", ", $model_has_brand_ids) . ")";
    }
}

if ($condition != 0 && $status == 0) {
    $query .= " WHERE `condition_condition_id` = '" . $condition . "'";
    $status = 1;
} elseif ($condition != 0 && $status != 0) {
    $query .= " AND `condition_condition_id` = '" . $condition . "'";
}

$product_id = 0;

if ($color != 0) {
    $color_rs = Database::search(
        "SELECT *
        FROM `product_has_color`
        WHERE
            `color_clr_id` = '" . $color . "'"
    );
    $color_num = $color_rs->num_rows;

    if ($color != 0 && $status == 0) {
        $color_data = $color_rs->fetch_assoc();
        $product_id = $color_data['product_id'];
    }

    if ($status == 0) {
        $query .= " WHERE `id` = '" . $product_id . "'";
        $status = 1;
    } elseif ($status != 0) {
        $query .= " AND `id` = '" . $product_id . "'";
    }
}

if (!empty($price_from) && empty($price_to)) {
    if ($status == 0) {
        $query .= " WHERE `price` >= '" . $price_from . "'";
        $status = 1;
    } elseif ($status != 0) {
        $query .= " AND `price` >= '" . $price_from . "'";
    }
}

if (empty($price_from) && !empty($price_to)) {
    if ($status == 0) {
        $query .= " WHERE `price <= '" . $price_to . "'";
        $status = 0;
    } elseif ($status != 0) {
        $query .= " AND `price` <= '" . $price_to . "'";
    }
}

if (!empty($price_from) && !empty($price_to)) {
    if ($status == 0) {
        $query .= " WHERE `price` BETWEEN '" . $price_from . "' AND '" . $price_to . "'";
        $status = 1;
    } elseif ($status != 0) {
        $query .= " AND `price` BETWEEN '" . $price_from . "' AND '" . $price_to . "'";
    }
}

if ($sort == 1) {
    $query .= " ORDER BY `price` ASC";
} elseif ($sort == 2) {
    $query .= " ORDER BY `price` DESC";
} elseif ($sort == 3) {
    $query .= " ORDER BY `qty` ASC";
} elseif ($sort == 4) {
    $query .= " ORDER BY `qty` DESC";
}

?>


<div class="row">
    <div class="col-12">
        <div class="row justify-content-center gap-2">

            <?php

            $pageNo;

            if ($_POST["page"] != 0) {
                $pageNo = $_POST["page"];
            } else {
                $pageNo = 1;
            }

            $product_rs = Database::search($query);
            $product_num = $product_rs->num_rows;

            $results_per_page = 5;
            $number_of_pages = ceil($product_num / $results_per_page);

            $page_results_offset = ($pageNo - 1) * $results_per_page;
            $selected_rs = Database::search(
                $query . " LIMIT $results_per_page OFFSET $page_results_offset"
            );

            $selected_num = $selected_rs->num_rows;
            for ($i = 0; $i < $selected_num; $i++) {
                $product_data = $selected_rs->fetch_assoc();

                $img_rs = Database::search(
                    "SELECT *
                    FROM `product_img`
                    WHERE
                        `product_id` = '" . $product_data['id'] . "'"
                );
                $img_data = $img_rs->fetch_assoc();

                ?>

                <div class="card col-6 col-lg-2 mt-2 mb-2">
                    <img src="<?php echo $img_data["img_path"]; ?>" alt="Product Image"
                        class="card-img-top img-thumbnail mt-2" style="height: 180px;" />
                    <div class="card-body ms-0 m-0 text-center">
                        <h5 class="card-title fw-bold fs-6">
                            <?php echo $product_data["title"]; ?>
                        </h5>
                        <span class="badge rounded-pill text-bg-info">New</span>
                        <br />
                        <span class="card-text text-primary">
                            Rs. <?php echo $product_data["price"]; ?>.00
                        </span>
                        <br />

                        <?php
                        if ($product_data["qty"] > 0) {
                            ?>

                            <span class="card-text text-warning fw-bold">
                                In Stock
                            </span><br />
                            <span class="card-text text-success fw-bold">
                                <?php echo $product_data["qty"]; ?> Items Available
                            </span>
                            <br /><br />
                            <a href='#' class="col-12 btn btn-success">Buy Now</a>
                            <button class="col-12 btn btn-dark mt-2">
                                <i class="bi bi-cart-plus-fill text-white fs-5"></i>
                            </button>

                            <button class="col-12 btn btn-outline-light mt-2 border border-primary">
                                <i class="bi bi-heart-fill text-danger fs-5"></i>
                            </button>

                            <?php
                        } else {
                            ?>

                            <span class="card-text text-danger fw-bold">
                                Out Of Stock
                            </span>
                            <br />
                            <br />
                            <a href='#' class="col-12 btn btn-success disabled">Buy Now</a>

                            <button class="col-12 btn btn-dark mt-2 disabled">
                                <i class="bi bi-cart-plus-fill text-white fs-5"></i>
                            </button>

                            <button class="col-12 btn btn-outline-light mt-2 border border-primary">
                                <i class="bi bi-heart-fill text-danger fs-5"></i>
                            </button>

                            <?php
                        }
                        ?>
                    </div>
                </div>

                <?php
            }
            ?>

            <div class="col-12 text-center mb-3">
                <nav aria-label="Page navigation example">
                    <ul class="pagination pagination-lg justify-content-center">
                        <li class="page-item">
                            <a class="page-link" <?php

                            if ($pageNo <= 1) {
                                echo "href='#'";
                            } else {
                                echo "onclick=advancedSearch(" . ($pageNo - 1) . ");";
                            }

                            ?> aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php

                        for ($i = 1; $i < $number_of_pages + 1; $i++) {
                            if ($i == $pageNo) {
                                ?>

                                <li class="page-item active">
                                    <a class="page-link" onclick="advancedSearch(<?php echo $i; ?>);">
                                        <?php echo $i; ?>
                                    </a>
                                </li>

                                <?php
                            } else {
                                ?>

                                <li class="page-item">
                                    <a class="page-link" onclick="advancedSearch(<?php echo $i; ?>);">
                                        <?php echo $i; ?>
                                    </a>
                                </li>

                                <?php
                            }
                        }

                        ?>


                        <li class="page-item">
                            <a class="page-link" <?php

                            if ($pageNo >= $number_of_pages) {
                                echo "href='#'";
                            } else {
                                echo "onclick=advancedSearch(" . ($pageNo + 1) . ");";
                            }

                            ?> aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>


        </div>
    </div>
</div>

<?php

session_start();
include "connection.php";

$user = $_SESSION["user"];
$email = $user["email"];

$search = $_POST['search'];
$time = $_POST['time'];
$qty = $_POST['qty'];
$condition = $_POST['condition'];

$search_q = "SELECT * FROM `product` WHERE `user_email` = '$email'";

if (!empty($search)) {
    $search_q .= " AND `title` LIKE '%" . $search . "%'";
}

if ($condition != 0) {
    $search_q .= " AND `condition_condition_id` = '$condition'";
}

if ($time == 1) {
    $search_q .= " ORDER BY `datetime_added` DESC";
} elseif ($time == 2) {
    $search_q .= " ORDER BY `datetime_added` ASC";
}

if ($time != 0 && $qty != 0) {
    if ($qty == 1) {
        $search_q .= " , `qty` DESC";
    } elseif ($qty == 2) {
        $search_q .= " , `qty` ASC";
    }
} elseif ($time == 0 && $qty != 0) {
    if ($qty == 1) {
        $search_q .= " ORDER BY `qty` DESC";
    } elseif ($qty == 2) {
        $search_q .= " ORDER BY `qty` ASC";
    }
}

?>

<div class="offset-1 col-10 text-center">
    <div class="row justify-content-center">

        <?php

        if ($_POST["page"] != 0) {
            $pageNo = $_POST["page"];
        } else {
            $pageNo = 1;
        }

        $product_rs = Database::search($search_q);
        $product_num = $product_rs->num_rows;

        $results_per_page = 5;
        $number_of_pages = ceil($product_num / $results_per_page);

        $page_results_offset = ($pageNo - 1) * $results_per_page;
        $selected_rs = Database::search(
            $search_q . " LIMIT $results_per_page OFFSET $page_results_offset"
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

                        <img src="<?php echo $product_img_data["img_path"]; ?>" alt="Product image"
                            class="img-fluid rounded-start" />
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
                                    onchange="changeStatus(<?php echo $product_data['id']; ?>);" role="switch"
                                    class="form-check-input" type="checkbox" <?php if ($product_data["status_status_id"] == 2) {
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
                <a class="page-link" <?php

                if ($pageNo <= 1) {
                    echo "href='#'";
                } else {
                    echo "onclick=sortMyProducts(" . ($pageNo - 1) . ");";
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
                        <a class="page-link" onclick="sortMyProducts(<?php echo "?page=" . $i; ?>);">
                            <?php echo $i; ?>
                        </a>
                    </li>

                    <?php
                } else {
                    ?>

                    <li class="page-item">
                        <a class="page-link" onclick="sortMyProducts(<?php echo "?page=" . $i; ?>);">
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
                    echo "onclick=sortMyProducts(" . ($pageNo + 1) . ");";
                }

                ?> aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

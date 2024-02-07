<!DOCTYPE html>
<html lang="en_US">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Selling History | Admins | eShop</title>

  <link rel="stylesheet" href="bootstrap.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="style.css" />

  <link rel="icon" href="resources/logo.svg" />
</head>

<body style="
      background-color: #74ebd5;
      background-image: linear-gradient(90deg, #74ebd5 0%, #9face6 100%);
    ">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 bg-light text-center">
        <label class="form-label text-primary fw-bold fs-1">
          Selling History
        </label>
      </div>

      <div class="col-12 bg-light mt-3 mb-3">

        <div class="row">
          <div class="col-12 col-lg-3 mt-3 mb-3">
            <label class="form-label fs-5">Search by Invoice ID : </label>
            <input type="text" class="form-control fs-5" id="search-text" onkeyup="searchInvoice();" />
          </div>
          <div class="col-12 col-lg-2 mt-3 mb-3"></div>
          <div class="col-12 col-lg-3 mt-3 mb-3">
            <label class="form-label fs-5">From Date : </label>
            <input type="date" class="form-control fs-5" id="from" />
          </div>
          <div class="col-12 col-lg-3 mt-3 mb-3">
            <label class="form-label fs-5">To Date : </label>
            <input type="date" class="form-control fs-5" id="to" />
          </div>
          <div class="col-12 col-lg-1 mt-3 mb-3 d-grid">
            <button class="btn btn-primary fs-5 fw-bold" onclick="findSellings();">Find</button>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="row">
          <div class="col-1 bg-secondary text-end">
            <label class="form-label fs-5 fw-bold text-white">
              Invoice ID
            </label>
          </div>
          <div class="col-3 bg-body text-end">
            <label class="form-label fs-5 fw-bold text-black">Product</label>
          </div>
          <div class="col-3 bg-secondary text-end">
            <label class="form-label fs-5 fw-bold text-white">Buyer</label>
          </div>
          <div class="col-2 bg-body text-end">
            <label class="form-label fs-5 fw-bold text-black">Amount</label>
          </div>
          <div class="col-1 bg-secondary text-end">
            <label class="form-label fs-5 fw-bold text-white">Quantity</label>
          </div>
          <div class="col-2 bg-white"></div>
        </div>
      </div>

      <div class="col-12 mt-2" id="view-area">
        <?php
        include "connection.php";

        $query = "SELECT * FROM `invoice`";
        $pageNo;

        if (isset($_GET["page"])) {
          $pageNo = $_GET["page"];
        } else {
          $pageNo = 1;
        }

        $invoice_rs = Database::search($query);
        $invoice_num = $invoice_rs->num_rows;

        $results_per_page = 20;
        $number_of_pages = ceil($invoice_num / $results_per_page);

        $page_results_offset = ($pageNo - 1) * $results_per_page;
        $selected_rs = Database::search(
          "SELECT *
          FROM `invoice`
          LIMIT $results_per_page
          OFFSET $page_results_offset"
        );

        $selected_num = $selected_rs->num_rows;
        for ($i = 0; $i < $selected_num; $i++) {
          $invoice_data = $selected_rs->fetch_assoc();
          ?>

          <div class="row">
            <div class="col-1 bg-secondary text-end">
              <label class="form-label fs-5 fw-bold text-white mt-1 mb-1">
                <?php echo $invoice_data["invoice_id"]; ?>
              </label>
            </div>

            <?php

            $product_rs = Database::search(
              "SELECT *
              FROM `product`
              WHERE
                `id` = '" . $invoice_data["product_id"] . "'"
            );
            $product_data = $product_rs->fetch_assoc();

            ?>

            <div class="col-3 bg-body text-end">
              <label class="form-label fs-5 fw-bold text-black mt-1 mb-1">
                <?php echo $product_data["title"]; ?>
              </label>
            </div>

            <?php

            $user_rs = Database::search(
              "SELECT *
              FROM `user`
              WHERE
                `email` = '" . $invoice_data["user_email"] . "'"
            );
            $user_data = $user_rs->fetch_assoc();

            ?>
            <div class="col-3 bg-secondary text-end">
              <label class="form-label fs-5 fw-bold text-white mt-1 mb-1">
                <?php echo $user_data["fname"] . " " . $user_data["lname"]; ?>
              </label>
            </div>
            <div class="col-2 bg-body text-end">
              <label class="form-label fs-5 fw-bold text-black mt-1 mb-1">
                Rs. <?php echo $invoice_data["total"]; ?>.00
              </label>
            </div>
            <div class="col-1 bg-secondary text-end">
              <label class="form-label fs-5 fw-bold text-white mt-1 mb-1">
                <?php echo $invoice_data["qty"]; ?>
              </label>
            </div>
            <div class="col-2 bg-white d-grid">
              <?php
              if ($invoice_data["status"] == 0) {
                ?>
                <button class="btn btn-success fw-bold mt-1 mb-1" id="btn-<?php echo $invoice_data["invoice_id"]; ?>"
                  onclick="changeInvoiceStatus('<?php echo $invoice_data['invoice_id']; ?>');">
                  Confirm Order
                </button>
                <?php
              } elseif ($invoice_data["status"] == 1) {
                ?>
                <button class="btn btn-warning fw-bold mt-1 mb-1" id="btn-<?php echo $invoice_data["invoice_id"]; ?>"
                  onclick="changeInvoiceStatus('<?php echo $invoice_data['invoice_id']; ?>');">
                  Packing
                </button>
                <?php
              } elseif ($invoice_data["status"] == 2) {
                ?>
                <button class="btn btn-info fw-bold mt-1 mb-1" id="btn-<?php echo $invoice_data["invoice_id"]; ?>"
                  onclick="changeInvoiceStatus('<?php echo $invoice_data['invoice_id']; ?>');">
                  Dispatch
                </button>
                <?php
              } elseif ($invoice_data["status"] == 3) {
                ?>
                <button class="btn btn-primary fw-bold mt-1 mb-1" id="btn-<?php echo $invoice_data["invoice_id"]; ?>"
                  onclick="changeInvoiceStatus('<?php echo $invoice_data['invoice_id']; ?>');">
                  Shipping
                </button>
                <?php
              } elseif ($invoice_data["status"] == 4) {
                ?>
                <button class="btn btn-danger fw-bold mt-1 mb-1" id="btn-<?php echo $invoice_data["invoice_id"]; ?>"
                  onclick="changeInvoiceStatus('<?php echo $invoice_data['invoice_id']; ?>');">
                  Delivered
                </button>
                <?php
              }
              ?>
            </div>
          </div>

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
      </div>
    </div>
  </div>

  <script src="bootstrap.bundle.js"></script>
  <script src="script.js"></script>
</body>

</html>

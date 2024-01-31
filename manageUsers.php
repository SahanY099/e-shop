<!DOCTYPE html>
<html lang="en_US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Users | Admins | eShop</title>

    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css" />

    <link rel="icon" href="resources/logo.svg" />

</head>

<body style="background-color: #74EBD5;background-image: linear-gradient(90deg,#74EBD5 0%,#9FACE6 100%);">

    <div class="container-fluid">
        <div class="row">

            <div class="col-12 bg-light text-center">
                <label class="form-label text-primary fw-bold fs-1">Manage All Users</label>
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
                                    <button class="btn btn-warning">Search User</button>
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
                            <span class="fs-4 fw-bold">Profile Image</span>
                        </div>
                        <div class="col-4 col-lg-2 bg-primary py-2">
                            <span class="fs-4 fw-bold text-white">User Name</span>
                        </div>
                        <div class="col-4 col-lg-2 d-lg-block bg-light py-2">
                            <span class="fs-4 fw-bold">Email</span>
                        </div>
                        <div class="col-2 d-none d-lg-block bg-primary py-2">
                            <span class="fs-4 fw-bold text-white">Mobile</span>
                        </div>
                        <div class="col-2 d-none d-lg-block bg-light py-2">
                            <span class="fs-4 fw-bold">Registered Date</span>
                        </div>
                        <div class="col-2 col-lg-1 bg-white"></div>
                    </div>
                </div>



                <?php
                $query = "SELECT * FROM `user`";
                $pageNo;

                if (isset($_GET["page"])) {
                    $pageNo = $_GET["page"];
                } else {
                    $pageNo = 1;
                }

                $user_rs = Database::search($query);
                $user_num = $user_rs->num_rows;

                $results_per_page = 20;
                $number_of_pages = ceil($user_num / $results_per_page);

                $page_results_offset = ($pageNo - 1) * $results_per_page;
                $selected_rs = Database::search(
                    "SELECT *
                FROM `user`
                LIMIT $results_per_page
                OFFSET $page_results_offset"
                );

                $selected_num = $selected_rs->num_rows;
                for ($i = 0; $i < $selected_num; $i++) {
                    $user_data = $selected_rs->fetch_assoc();
                    ?>

                    <div class="col-12 mt-3 mb-3">
                        <div class="row">
                            <div class="col-2 col-lg-1 bg-primary py-2 text-end">
                                <span class="fs-4 text-dark">
                                    <?php echo $i + 1; ?>
                                </span>
                            </div>
                            <div class="col-2 d-none d-lg-block bg-light py-2"
                                onclick="viewMsgModal('<?php echo $user_data['email']; ?>');">

                                <?php

                                $profile_image_rs = Database::search(
                                    "SELECT *
                                FROM `profile_img`
                                WHERE
                                    `user_email` = '" . $user_data["email"] . "'"
                                );
                                $profile_img_num = $profile_image_rs->num_rows;

                                if ($profile_img_num == 1) {
                                    $profile_img_path = ($profile_image_rs->fetch_assoc())["path"];
                                } else {
                                    $profile_img_path = "resources/new_user.svg";
                                }

                                ?>

                                <img src="<?php echo $profile_img_path; ?>" style="height: 40px;margin-left: 80px;" />
                            </div>
                            <div class="col-4 col-lg-2 bg-primary py-2">
                                <span class="fs-4 text-dark">
                                    <?php echo $user_data['fname'] . " " . $user_data['lname']; ?>
                                </span>
                            </div>
                            <div class="col-4 col-lg-2 d-lg-block bg-light py-2">
                                <span class="fs-4 ">
                                    <?php echo $user_data['email']; ?>
                                </span>
                            </div>
                            <div class="col-2 d-none d-lg-block bg-primary py-2">
                                <span class="fs-4 text-dark">
                                    <?php echo $user_data['mobile']; ?>
                                </span>
                            </div>
                            <div class="col-2 d-none d-lg-block bg-light py-2">
                                <span class="fs-4 ">
                                    <?php
                                    $split_date = explode(" ", $user_data["joined_date"]);
                                    echo $split_date[0];
                                    ?>
                                </span>
                            </div>
                            <div class="col-2 col-lg-1 bg-white py-2 d-grid">

                                <?php

                                if ($user_data["status_status_id"] == 1) {
                                    ?>
                                    <button class="btn btn-danger" id="user-block-<?php $user_data['email']; ?>"
                                        onclick="blockUser('<?php echo $user_data['email']; ?>');">Block</button>
                                    <?php
                                } else {
                                    ?>
                                    <button class="btn btn-success" id="user-unblock-<?php $user_data['email']; ?>"
                                        onclick="blockUser('<?php echo $user_data['email']; ?>');">Unblock</button>
                                    <?php
                                }

                                ?>

                            </div>

                        </div>
                    </div>

                    <!-- msg modal -->
                    <div class="modal" tabindex="-1" id="msg-modal-<?php echo $user_data['email']; ?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <?php echo $user_data['fname'] . " " . $user_data['lname']; ?>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body overflow-scroll">
                                    <!-- received -->
                                    <div class="col-12 mt-2">
                                        <div class="row">
                                            <div class="col-8 rounded bg-success">
                                                <div class="row">
                                                    <div class="col-12 pt-2">
                                                        <span class="text-white fw-bold fs-4">Hello there!!!</span>
                                                    </div>
                                                    <div class="col-12 text-end pb-2">
                                                        <span class="text-white fs-6">2022-11-9 00:00:00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- received -->
                                    <!-- sent -->
                                    <div class="col-12 mt-2">
                                        <div class="row">
                                            <div class="offset-4 col-8 rounded bg-primary">
                                                <div class="row">
                                                    <div class="col-12 pt-2">
                                                        <span class="text-white fw-bold fs-4">Hello there!!!</span>
                                                    </div>
                                                    <div class="col-12 text-end pb-2">
                                                        <span class="text-white fs-6">2022-11-9 00:00:00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- sent -->

                                </div>
                                <div class="modal-footer">

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-9">
                                                <input type="text" class="form-control" id="msg-txt" />
                                            </div>
                                            <div class="col-3 d-grid">
                                                <button type="button" class="btn btn-primary">Send</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- msg modal -->

                    <?php
                } ?>

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

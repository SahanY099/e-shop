<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Messages | eShop</title>

    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="style.css" />

    <link rel="icon" href="resources/logo.svg" />
</head>

<body style="background-color: #74ebd5;
      background-image: linear-gradient(90deg, #74ebd5 0%, #9face6 100%);">
    <div class="container-fluid">
        <div class="row">
            <?php
            include "header.php";
            include "connection.php";

            $email = $_SESSION["user"]["email"];

            ?>

            <div class="col-12">
                <hr />
            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-2">
                        <label for="" class="form-label fs-4 fw-bold">
                            Select Receiver :
                        </label>
                    </div>
                    <div class="col-4">
                        <select id="select-user" class="form-select">
                            <option value="0" disabled>Select User</option>

                            <?php
                            $select_user_rs = Database::search(
                                "SELECT *
                                FROM `user`"
                            );
                            $select_user_num = $select_user_rs->num_rows;

                            for ($i = 0; $i < $select_user_num; $i++) {
                                $user_data = $select_user_rs->fetch_assoc();

                                if ($user_data["email"] != $email) {
                                    ?>

                                    <option value="<?php echo $user_data["email"]; ?>">
                                        <?php echo $user_data["fname"] . " " . $user_data["lname"]; ?>
                                    </option>

                                    <?php
                                }
                            }

                            ?>

                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 py-5 px-4">
                <div class="row overflow-hidden shadow rounded">
                    <div class="col-12 col-lg-5 px-0">
                        <div class="bg-white">
                            <div class="bg-light px-4 py-2">
                                <div class="col-12">
                                    <h5 class="mb-0 py-1">Recent</h5>
                                </div>
                                <div class="col-12">

                                    <!--  -->
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                                aria-selected="true">
                                                Received
                                            </button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                                data-bs-target="#profile" type="button" role="tab"
                                                aria-controls="profile" aria-selected="false">
                                                Sent
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                                            aria-labelledby="home-tab">
                                            <div class="message_box" id="message-box">

                                                <?php

                                                $sent_msg_rs = Database::search(
                                                    "SELECT DISTINCT *
                                                    FROM `chat`
                                                    WHERE
                                                        `to` = '$email'
                                                    ORDER BY `date_time` DESC"
                                                );
                                                $received_msg_num = mysqli_num_rows($sent_msg_rs);

                                                for ($x = 0; $x < $received_msg_num; $x++) {
                                                    $msg_data = $sent_msg_rs->fetch_assoc();

                                                    $sender = $msg_data["from"];

                                                    $user_rs = Database::search(
                                                        "SELECT *
                                                        FROM `user`
                                                        LEFT JOIN `profile_img`
                                                            ON user.email = profile_img.user_email
                                                        WHERE
                                                            `email` = '$sender'"
                                                    );
                                                    $user_data = $user_rs->fetch_assoc();

                                                    if (isset($user_data["path"])) {
                                                        $profile_img_path = $user_data["path"];
                                                    } else {
                                                        $profile_img_path = "resources/new_user.svg";
                                                    }

                                                    if ($msg_data["status"] == 0) {
                                                        ?>

                                                        <div class="list-group rounded-0"
                                                            onclick="viewMessage('<?php echo $sender; ?>');">
                                                            <a href="#"
                                                                class="list-group-item list-group-item-action text-white rounded-0 bg-primary">
                                                                <div class="media">
                                                                    <img src="<?php echo $profile_img_path; ?>" width="50px"
                                                                        class="rounded-circle" />

                                                                    <div class="me-4">
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between mb-1">
                                                                            <h6 class="mb-0 fw-bold">
                                                                                <?php echo $user_data["fname"] . " " . $user_data["lname"] ?>
                                                                            </h6>
                                                                            <small class="small fw-bold">
                                                                                <?php $msg_data["date_time"]; ?>
                                                                            </small>
                                                                        </div>
                                                                        <p class="mb-0">
                                                                            <?php echo $msg_data["content"]; ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <?php
                                                    } else {
                                                        ?>

                                                        <div class="list-group rounded-0"
                                                            onclick="viewMessage('<?php echo $sender; ?>');">
                                                            <a href="#"
                                                                class="list-group-item list-group-item-action text-dark rounded-0 bg-body">
                                                                <div class="media">
                                                                    <img src="<?php echo $profile_img_path; ?>" width="50px"
                                                                        class="rounded-circle" />


                                                                    <div class="me-4">
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between mb-1">
                                                                            <h6 class="mb-0 fw-bold">
                                                                                <?php echo $user_data["fname"] . " " . $user_data["lname"]; ?>
                                                                            </h6>
                                                                            <small class="small fw-bold">
                                                                                <?php $msg_data["date_time"]; ?>
                                                                            </small>
                                                                        </div>
                                                                        <p class="mb-0">
                                                                            <?php echo $msg_data["content"]; ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <?php
                                                    }
                                                }

                                                ?>

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel"
                                            aria-labelledby="profile-tab">
                                            <div class="message_box" id="message-box">

                                                <?php

                                                $sent_msg_rs = Database::search(
                                                    "SELECT DISTINCT *
                                                    FROM `chat`
                                                    WHERE
                                                        `from` = '$email'
                                                    ORDER BY `date_time` DESC"
                                                );
                                                $sent_msg_num = mysqli_num_rows($sent_msg_rs);

                                                for ($x = 0; $x < $sent_msg_num; $x++) {
                                                    $msg_data = $sent_msg_rs->fetch_assoc();

                                                    $receiver = $msg_data["to"];

                                                    $user_rs = Database::search(
                                                        "SELECT *
                                                        FROM `user`
                                                        LEFT JOIN `profile_img`
                                                            ON user.email = profile_img.user_email
                                                        WHERE
                                                            `email` = '$receiver'"
                                                    );

                                                    $user_data = $user_rs->fetch_assoc();

                                                    if (isset($user_data["path"])) {
                                                        $profile_img_path = $user_data["path"];
                                                    } else {
                                                        $profile_img_path = "resources/new_user.svg";
                                                    }

                                                    ?>

                                                    <div class="list-group rounded-0"
                                                        onclick="viewMessage('<?php echo $receiver; ?>');">
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action text-black rounded-0 bg-secondary">
                                                            <div class="media">
                                                                <img src="<?php echo $profile_img_path; ?>" width="50px"
                                                                    class="rounded-circle" />

                                                                <div class="me-4">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between mb-1">
                                                                        <h6 class="mb-0 fw-bold">me</h6>
                                                                        <small class="small fw-bold">
                                                                            <?php $msg_data["date_time"]; ?>
                                                                        </small>
                                                                    </div>
                                                                    <p class="mb-0">
                                                                        <?php echo $msg_data["content"]; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <?php
                                                }

                                                ?>


                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-7 px-0">
                        <div class="row px-4 py-5 text-white chat_box" id="chat-box">
                            <!-- view area -->
                        </div>
                        <!-- txt -->
                        <div class="col-12 px-2">
                            <div class="row">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control rounded border-0 py-3 bg-light"
                                        placeholder="Type a message ..." aria-describedby="send_btn" id="msg-text" />
                                    <button class="btn btn-light fs-2" id="send-btn" onclick="sendMsg()">
                                        <i class="bi bi-send-fill fs-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- txt -->
                    </div>
                </div>
            </div>

            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="bootstrap.bundle.js"></script>
    <script src="script.js"></script>
</body>

</html>

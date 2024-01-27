<?php

session_start();

include "connection.php";

$receiver_email = $_SESSION["user"]["email"];
$sender_email = $_GET["email"];

$msg_rs = Database::search(
    "SELECT *
    FROM `chat`
    WHERE
        `from` = '$sender_email' OR
        `to` = '$sender_email'"
);
$msg_num = $msg_rs->num_rows;

for ($i = 0; $i < $msg_num; $i++) {
    $msg_data = $msg_rs->fetch_assoc();

    if ($msg_data["from"] == $sender_email && $msg_data["to"] == $receiver_email) {

        $user_rs = Database::search(
            "SELECT *
            FROM `user`
            LEFT JOIN `profile_img` ON
                user.email = profile_img.user_email
            WHERE
                `email` ='" . $msg_data["from"] . "'"
        );
        $user_data = $user_rs->fetch_assoc();

        if (isset($user_data["path"])) {
            $profile_img_path = $user_data["path"];
        } else {
            $profile_img_path = "resources/new_user.svg";
        }

        ?>

        <div class="media w-75 ">
            <img src="<?php echo $profile_img_path; ?>" width="50px" class="mb-2 rounded-circle" />
            <div class="media-body me-4">
                <div class="bg-light rounded py-2 px-3 mb-2">
                    <p class="mb-0 fw-bold text-black-50">
                        <?php echo $msg_data["content"]; ?>
                    </p>
                </div>
                <p class="small fw-bold text-black-50 text-end">
                    <?php echo $msg_data["date_time"]; ?>
                </p>
                <p class="invisible" id="rmail">
                    <?php echo $msg_data["from"]; ?>
                </p>
            </div>
        </div>

        <?php
    } elseif ($msg_data["to"] == $sender_email && $msg_data["from"] == $receiver_email) {

        $user_rs = Database::search(
            "SELECT *
            FROM `user`
            LEFT JOIN `profile_img` ON
                user.email = profile_img.user_email
            WHERE
                `email` = '" . $msg_data["to"] . "'"
        );
        $user_data = $user_rs->fetch_assoc();

        if (isset($user_data["path"])) {
            $profile_img_path = $user_data["path"];
        } else {
            $profile_img_path = "resources/new_user.svg";
        }

        ?>

        <div class="offset-3 col-9 media w-75 text-end justify-content-end align-items-end">
            <div class="media-body">
                <div class="bg-primary rounded py-2 px-3 mb-2">
                    <p class="mb-0 text-white"><?php echo $msg_data["content"]; ?></p>
                </div>
                <p class="small fw-bold text-black-50 text-end"><?php echo $msg_data["date_time"]; ?></p>
            </div>
        </div>

        <?php
    }

    if ($msg_data["status"] == 0) {
        Database::iud(
            "UPDATE `chat`
            SET
                `status` = 1
            WHERE
                `chat_id` = '" . $msg_data["chat_id"] . "'"
        );
    }
}

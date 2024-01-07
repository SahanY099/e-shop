<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Profile | eShop</title>

    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css" />

    <link rel="icon" href="resources/logo.svg" />

</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <?php
            include "header.php";
            include "connection.php";

            if (isset($_SESSION["user"])) {
                $user = $_SESSION["user"];
                $email = $user["email"];

                $user_details_rs = Database::search(
                    "SELECT *
                    FROM `user`
                    INNER JOIN `gender` ON
                        user.gender_gender_id=gender.gender_id
                    WHERE `email` = '$email'"
                );

                $image_rs = Database::search("SELECT * FROM `profile_img` WHERE `user_email` = '$email'");
                $address_rs = Database::search(
                    "SELECT *
                    FROM `user_has_address`
                    INNER JOIN `city` ON
                        user_has_address.city_city_id=city.city_id
                    INNER JOIN `district` ON
                        city.district_district_id=district.district_id
                    INNER JOIN `province` ON
                        district.province_province_id=province.province_id
                    WHERE `user_email` = '$email'"
                );

                $user_details = $user_details_rs->fetch_assoc();
                $image_details = $image_rs->fetch_assoc();
                $address_details = $address_rs->fetch_assoc();
                ?>

                <div class="col-12 bg-primary">
                    <div class="row">

                        <div class="col-12 bg-body rounded mt-4 mb-4">
                            <div class="row g-2">

                                <div class="col-md-3 border-end">
                                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">

                                        <?php
                                        if (empty($image_details["path"])) {
                                            ?>

                                            <img src="resources/new_user.svg" class="rounded mt-5" style="width: 150px;"
                                                alt="Profile Image" id="profile-image" />

                                            <?php
                                        } else {
                                            ?>

                                            <img class="rounded mt-5" style="width: 150px;" alt="Profile Image"
                                                id="profile-image" src="<?php echo $image_details["path"]; ?>" />

                                            <?php
                                        }

                                        ?>

                                        <span class="fw-bold">
                                            <?php echo $user_details["fname"] . " " . $user_details["lname"]; ?>
                                        </span>
                                        <span class="fw-bold text-black-50">
                                            <?php echo $user_details["email"] ?>
                                        </span>

                                        <input type="file" class="d-none" id="profile-image-selector" />
                                        <label for="profile-image-selector" class="btn btn-primary mt-5"
                                            onclick="changeProfileImage();">
                                            Update Profile Image
                                        </label>

                                    </div>
                                </div>

                                <div class="col-md-5 border-end">
                                    <div class="p-3 py-5">

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="fw-bold">Profile Settings</h4>
                                        </div>

                                        <div class="row mt-4">

                                            <div class="col-6">
                                                <label class="form-label">First Name</label>
                                                <input id="fname" type="text" class="form-control"
                                                    value="<?php echo $user_details["fname"] ?>" />
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="lname"
                                                    value="<?php echo $user_details["lname"] ?>" />
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Mobile</label>
                                                <input type="text" class="form-control" id="mobile"
                                                    value="<?php echo $user_details["mobile"] ?>" />
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" readonly
                                                        value="<?php echo $user_details["password"] ?>" />
                                                    <span class="input-group-text bg-primary" id="basic-addon2">
                                                        <i class="bi bi-eye-slash-fill text-white"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control" readonly
                                                    value="<?php echo $user_details["email"] ?>" />
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Registered Date</label>
                                                <input type="text" class="form-control" readonly
                                                    value="<?php echo $user_details["joined_date"] ?>" />
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Address Line 01</label>

                                                <?php
                                                if (empty($address_details["line1"])) {
                                                    ?>

                                                    <input id="line1" type="text" class="form-control" />

                                                    <?php
                                                } else {
                                                    ?>

                                                    <input id="line1" type="text" class="form-control"
                                                        value="<?php echo $address_details["line1"] ?>" />

                                                    <?php
                                                }
                                                ?>

                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Address Line 02</label>

                                                <?php
                                                if (empty($address_details["line2"])) {
                                                    ?>

                                                    <input id="line2" type="text" class="form-control" />

                                                    <?php
                                                } else {
                                                    ?>

                                                    <input id="line2" type="text" class="form-control"
                                                        value="<?php echo $address_details["line2"] ?>" />

                                                    <?php
                                                }
                                                ?>

                                            </div>

                                            <?php
                                            $province_rs = Database::search("SELECT * FROM `province`");
                                            $district_rs = Database::search("SELECT * FROM `district`");
                                            $city_rs = Database::search("SELECT * FROM `city`");
                                            ?>

                                            <div class="col-6">
                                                <label class="form-label">Province</label>
                                                <select id="province" class="form-select">
                                                    <option value="">Select Province</option>

                                                    <?php
                                                    for ($x = 0; $x < $province_rs->num_rows; $x++) {
                                                        $province = $province_rs->fetch_assoc();
                                                        ?>

                                                        <option value="<?php echo $province["province_id"] ?>" <?php
                                                           if (
                                                               !empty($address_details["province_id"]) &&
                                                               $address_details["province_id"] == $province["province_id"]
                                                           ) {
                                                               echo "selected";
                                                           }
                                                           ?>>
                                                            <?php echo $province["province_name"] ?>

                                                        </option>

                                                        <?php
                                                    }
                                                    ?>

                                                </select>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label">District</label>
                                                <select id="district" class="form-select">
                                                    <option value="0">Select District</option>

                                                    <?php
                                                    for ($x = 0; $x < $district_rs->num_rows; $x++) {
                                                        $district = $district_rs->fetch_assoc();
                                                        ?>

                                                        <option value="<?php echo $district["district_id"] ?>" <?php
                                                           if (
                                                               !empty($address_details["district_id"]) &&
                                                               $address_details["district_id"] == $district["district_id"]
                                                           ) {
                                                               echo "selected";
                                                           }
                                                           ?>>
                                                            <?php echo $district["district_name"] ?>

                                                        </option>

                                                        <?php
                                                    }
                                                    ?>

                                                </select>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label">City</label>
                                                <select id="city" class="form-select">
                                                    <option value="0">Select City</option>

                                                    <?php
                                                    for ($x = 0; $x < $city_rs->num_rows; $x++) {
                                                        $city = $city_rs->fetch_assoc();
                                                        ?>

                                                        <option value="<?php echo $city["city_id"] ?>" <?php
                                                           if (
                                                               !empty($address_details["city_id"]) &&
                                                               $address_details["city_id"] == $city["city_id"]
                                                           ) {
                                                               echo "selected";
                                                           }
                                                           ?>>
                                                            <?php echo $city["city_name"] ?>

                                                        </option>

                                                        <?php
                                                    }
                                                    ?>

                                                </select>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label">Postal Code</label>

                                                <?php
                                                if (empty($address_details["postal_code"])) {
                                                    ?>

                                                    <input id="postal-code" type="text" class="form-control" />

                                                    <?php
                                                } else {
                                                    ?>

                                                    <input id="postal-code" type="text" class="form-control"
                                                        value="<?php echo $address_details["postal_code"]; ?>" />

                                                    <?php
                                                }
                                                ?>

                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Gender</label>
                                                <input type="text" class="form-control"
                                                    value="<?php echo $user_details["gender_name"]; ?>" readonly />

                                            </div>

                                            <div class="col-12 d-grid mt-2">
                                                <button class="btn btn-primary" onclick="updateProfile()">Update My
                                                    Profile</button>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-4 text-center">
                                    <div class="row">
                                        <span class="fw-bold text-black-50 mt-5">Display ads</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <?php
            } else {
                ?>

                <script>
                    window.location = "index.php"
                </script>

                <?php
            }
            ?>



            <?php include "footer.php"; ?>

        </div>
    </div>

    <script src="bootstrap.bundle.js"></script>
    <script src="script.js"></script>
</body>

</html>

<?php

include "connection.php";


$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];
$mobile = $_POST['mobile'];
$gender = $_POST['gender'];

if (empty($fname)) {
    echo "Please enter your first name";
} elseif (strlen($fname) > 50) {
    echo "First name must contain lower than 50 characters" . " " . strlen($fname) > 50;
} elseif (empty($lname)) {
    echo "Please enter your last name";
} elseif (strlen($lname) > 50) {
    echo "Last name must contain lower than 50 characters";
} elseif (empty($email)) {
    echo "Please enter your email";
} elseif (strlen($email) > 100) {
    echo "Email must contain lower than 100 characters";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please enter a valid email";
} elseif (empty($password)) {
    echo "Please enter your password";
} elseif (strlen($password) < 5 || strlen($password) > 20) {
    echo "Password must be between 5 and 20 characters";
} elseif (strlen($mobile) != 10) {
    echo "Your mobile number must be 10 digits";
} elseif (!preg_match('/07[0,1,2,4,5,6,7,8]\d{7}/', $mobile)) {
    echo "Please Enter Valid Mobile Number";
} else {
    $q = "SELECT * FROM `user` WHERE `email` = '$email' OR `mobile` = '$mobile'";
    $result = Database::search($q);

    if ($result->num_rows == 1) {
        echo "Email or Mobile Number Already Exists";
    } else {
        $date = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $date->setTimezone($tz);
        $date = $date->format('Y-m-d H:i:s');

        $q = "INSERT INTO `user` (
                `fname`,
                `lname`,
                `email`,
                `password`,
                `mobile`,
                `joined_date`,
                `gender_gender_id`,
                `status_status_id`
            ) VALUES (
                '$fname', '$lname', '$email', '$password',
                 '$mobile', '$date', '$gender', '1'
            )
        ";

        Database::iud($q);

        echo "success";
    }
}

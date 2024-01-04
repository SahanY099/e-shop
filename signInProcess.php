<?php

session_start();

include "connection.php";

$email = $_POST['email'];
$password = $_POST['password'];
$rememberMe = $_POST['rememberMe'];

if (empty($email)) {
    echo "Please Enter Your Email";
} elseif (empty($password)) {
    echo "Please Enter Your Password";
} else {
    $q = "SELECT * FROM `user` WHERE `email` = '$email' AND `password` = '$password'";
    $result = Database::search($q);

    if ($result->num_rows == 1) {
        echo "success";
        $data = $result->fetch_assoc();
        $_SESSION["user"] = $data;

        if ($rememberMe == "true") {
            setcookie("email", $email, time() + 60 * 60 * 24 * 7);
            setcookie("password", $password, time() + 60 * 60 * 24 * 7);
        } else {
            setcookie("email", "", -1);
            setcookie("password", "", -1);
        }

    } else {
        echo "Incorrect Email or Password";
    }
}

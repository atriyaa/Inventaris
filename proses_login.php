<?php
    session_start();
    include "config/database.php";

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['admin'] = true;
        $_SESSION['username'] = $username;

        header("Location: Admin/dashboard_baru.php");
    } else {
        header("Location: login.php?error=1");
    }
?>
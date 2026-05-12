<?php

session_start();

include "config/database.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "
    SELECT *
    FROM admin
    WHERE username='$username'
    AND password='$password'
");

if (mysqli_num_rows($query) > 0) {

    $data = mysqli_fetch_assoc($query);

    $_SESSION['admin'] = true;

    $_SESSION['id_admin'] = $data['id'];

    $_SESSION['username'] = $data['username'];

    header("Location: Admin/dashboard_baru.php");

} else {

    header("Location: login.php?error=1");
}
?>
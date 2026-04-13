<?php
$host ="sql211.infinityfree.com";
$user = "if0_41648118";
$pass = "t7igmbaLep";
$db = "inventaris";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>
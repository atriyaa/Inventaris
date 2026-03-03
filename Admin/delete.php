<?php
    require_once __DIR__ . "/../config/database.php";
    if (!isset($_GET['id'])) {
        die("ID tidak ditemukan");
    }
    $id = (int) $_GET['id'];

    $query = "DELETE FROM barang WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("location: dashboard.php");
        exit();
    } else {
        echo "Gagal menghapus data";
    }
?>
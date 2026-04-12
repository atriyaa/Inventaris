<?php
    require_once __DIR__ . "/../config/database.php";
    if (!isset($_GET['id'])) {
        die("ID tidak ditemukan");
    }
    $id = (int) $_GET['id'];
    $cek = mysqli_query($conn, "SELECT * FROM peminjaman WHERE barang_id='$id'");
    $jumlah = mysqli_num_rows($cek);

    if($jumlah > 0) {
        echo "<script>alert('Barang tidak bisa dihapus karena sudah pernah dipinjam'); 
         window.location='dashboard.php';</script>";
    } else {
        mysqli_query($conn, "DELETE FROM barang WHERE id='$id'");
        header("Location: dashboard.php?delete=success");
    }

    $query = "DELETE FROM barang WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php?delete=success");
        exit();
    } else {
        echo "Gagal menghapus data";
    }
?>
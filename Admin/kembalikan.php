<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/database.php';

$id = $_GET['id'];

$data = mysqli_query($conn, "
    SELECT * FROM peminjaman WHERE id='$id'
");
$p = mysqli_fetch_assoc($data);

// update status
mysqli_query($conn, "
    UPDATE peminjaman SET
    status='dikembalikan',
    tanggal_kembali = NOW()
    WHERE id='$id'
");

// kembalikan stok
mysqli_query($conn, "
    UPDATE barang SET jumlah = jumlah + {$p['jumlah']}
    WHERE id = {$p['barang_id']}
");

header("Location: peminjaman.php");
?>
<?php
include "config/database.php";

$barang_id = $_POST['barang_id'];
$jumlah = $_POST['jumlah'];
$nama = $_POST['nama'];
$keperluan = $_POST['keperluan'];

$data = mysqli_query($conn, "SELECT jumlah FROM barang WHERE id='$barang_id'");
$barang = mysqli_fetch_assoc($data);

if ($barang['jumlah'] < $jumlah) {
    echo "Stok tidak cukup";
    exit; 
} else {
    echo "Peminjaman Berhasil";
}
mysqli_query($conn, "INSERT INTO peminjaman (nama_peminjam, keperluan, barang_id, jumlah, tanggal_pinjam, status) VALUES ('$nama', '$keperluan', '$barang_id', '$jumlah', NOW(), 'dipinjam')");

mysqli_query($conn, "UPDATE barang SET jumlah = jumlah - $jumlah WHERE id = '$barang_id'");

header("Location: index.php?pinjam=success");
?>
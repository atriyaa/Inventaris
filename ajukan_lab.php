<?php
include "config/database.php";

$nama = $_POST['nama'];
$lab = $_POST['lab'];
$keperluan = $_POST['keperluan'];
$tanggal = $_POST['tanggal'];

mysqli_query($conn, "
    INSERT INTO peminjaman_lab
    (nama_peminjam, lab, keperluan, tanggal)
    VALUES
    ('$nama', '$lab', '$keperluan', '$tanggal')
");

header("Location: index.php?status=menunggu");
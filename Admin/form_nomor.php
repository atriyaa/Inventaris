<?php
require_once __DIR__ . "/../config/database.php";

function bulanRomawi($bulan) {
    $romawi = [
        1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',
        7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'
    ];
    return $romawi[(int)$bulan];
}

$id = $_POST['id'];
$no = $_POST['no_surat'];

$no = str_pad($no, 3, "0", STR_PAD_LEFT);
$bulan = bulanRomawi(date('n'));
$tahun = date('Y');

$nomor_surat = "$no/LAB-IF/$bulan/$tahun";

mysqli_query($conn, "
    UPDATE peminjaman 
    SET nomor_surat = '$nomor_surat'
    WHERE id = $id
");

header("Location: cetak_surat.php?id=$id");
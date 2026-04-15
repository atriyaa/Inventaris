<?php
    require_once __DIR__ . "/../config/database.php";
    error_reporting(0);
    if (isset($_POST['nama_kategori'])) {
        $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

        $query = mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");

        if ($query) {
            // Jika berhasil, balikkan ke halaman tambah barang
            header("Location: create.php?pesan=tambah_kat_sukses");
        } else {
            echo "Gagal: " . mysqli_error($conn);
        }
    } else {
        // Jika diakses langsung tanpa kirim data
        header("Location: create.php");
    }
?>
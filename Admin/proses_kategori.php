<?php
    require_once __DIR__ . "/../config/database.php";
    error_reporting(0);
    if (isset($_POST['simpan_kategori'])) {
        $nama = mysqli_real_escape_string($conn, $_POST['simpan_kategori']);

        $query = mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");

        if ($query) {
            // Jika berhasil, balikkan ke halaman tambah barang
            header("Location: tambah_data.php?pesan=tambah_kat_sukses");
        } else {
            echo "Gagal: " . mysqli_error($conn);
        }
    } else {
        // Jika diakses langsung tanpa kirim data
        header("Location: create.php");
    }
?>
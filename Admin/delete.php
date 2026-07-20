<?php
require_once __DIR__ . "/../config/database.php";

// 1. Cek parameter ID Master Barang dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID barang tidak ditemukan.");
}

$id_barang = (int) $_GET['id'];

/* 
 2. Cek Relasi Berantai menggunakan JOIN:
    Master Barang (id_barang) -> Detail Barang (id_detail) -> Peminjaman Detail
*/
$query_cek = "
    SELECT pd.* 
    FROM peminjaman_detail pd
    JOIN barang_detail db ON pd.id_detail = db.id_detail
    WHERE db.id_barang = '$id_barang'
";

$cek = mysqli_query($conn, $query_cek);

if (!$cek) {
    die("Error Query Check: " . mysqli_error($conn));
}

// 3. Jika ditemukan riwayat peminjaman, batalkan hapus
if (mysqli_num_rows($cek) > 0) {
    echo "<script>
            alert('Barang ini tidak bisa dihapus karena unit fisiknya sedang atau pernah memiliki riwayat peminjaman!'); 
            window.location='inventaris.php';
          </script>";
    exit();
} else {
    /* 
     4. Jika TIDAK ADA riwayat peminjaman:
        a. Hapus dulu semua detail/unit fisik barangnya dari `detail_barang` (jika ada)
        b. Baru hapus master barangnya dari `barang`
    */
    
    // Hapus unit fisik di detail_barang terlebih dahulu (Foreign Key safety)
    mysqli_query($conn, "DELETE FROM barang_detail WHERE id_barang = '$id_barang'");

    // Hapus Master Barang
    $stmt = mysqli_prepare($conn, "DELETE FROM barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_barang);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: inventaris.php?delete=success");
        exit(); 
    } else {
        echo "Gagal menghapus master barang: " . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
}
?>
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
SELECT COUNT(*) AS total_proses
FROM peminjaman_detail pd
JOIN barang_detail bd ON pd.id_detail = bd.id_detail
JOIN peminjaman p ON pd.id_peminjaman = p.id_peminjaman
WHERE bd.id_barang = '$id_barang'
AND p.status_pinjam = 'Proses'
";

$cek = mysqli_query($conn, $query_cek);

if (!$cek) {
    die("Error Query Check: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($cek);

if ($data['total_proses'] > 0) {
    echo "<script>
            alert('Barang tidak dapat dihapus karena masih ada unit yang sedang dipinjam.');
            window.location='inventaris.php';
          </script>";
    exit();
} else {
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
    /* 
     4. Jika TIDAK ADA riwayat peminjaman:
        a. Hapus dulu semua detail/unit fisik barangnya dari `detail_barang` (jika ada)
        b. Baru hapus master barangnya dari `barang`
    */
    
    // Hapus unit fisik di detail_barang terlebih dahulu (Foreign Key safety)
}
?>
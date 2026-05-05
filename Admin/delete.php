<?php
// 1. Pastikan path benar. Hapus titik sebelum folder config jika menggunakan __DIR__
require_once __DIR__ . "/config/database.php";

if (!isset($_GET['$conn'])) {
    die("ID tidak ditemukan");
}

$id = (int) $_GET['id'];

// 2. Cek apakah barang sedang/pernah dipinjam (Relational Integrity)
$cek = mysqli_query($conn, "SELECT * FROM peminjaman WHERE barang_id='$id'");
$jumlah = mysqli_num_rows($cek);

if($jumlah > 0) {
    // JIKA ADA DATA: Gunakan JavaScript untuk Redirect
    // Jangan gunakan header() setelah echo!
    echo "<script>
            alert('Barang tidak bisa dihapus karena sudah pernah dipinjam'); 
            window.location='dashboard.php';
          </script>";
    exit(); // Penting: Hentikan script di sini
} else {
    // JIKA TIDAK ADA DATA: Lakukan penghapusan
    $query = "DELETE FROM barang WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        // Redirect ke satu tujuan yang pasti
        header("Location: dashboard_baru.php?delete=success");
        exit(); 
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
}
?>
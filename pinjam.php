<?php
require_once "config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barang_id = $_POST['barang_id'];
    $jumlah = $_POST['jumlah'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $keperluan = $_POST['keperluan'];

    mysqli_query($conn, "INSERT INTO peminjaman (barang_id, jumlah, nama_peminjam, keperluan) VALUES ('$barang_id', '$jumlah', '$nama_peminjam', '$keperluan')");
    header("Location: Admin/dashboard.php?pinjam=success");
    echo "Peminjaman Berhasil";
    exit;
}
$barang = mysqli_query($conn,
    "SELECT barang.*, kategori.nama_kategori
     FROM barang
     JOIN kategori ON barang.kategori_id = kategori.id
     WHERE barang.id = $id"
);
$jml_barang_pinjam = mysqli_query($conn, 
    "SELECT * FROM peminjaman WHERE peminjaman.id = $id"
);

$data = mysqli_fetch_assoc($barang);
$data_pinjam = mysqli_fetch_assoc($jml_barang_pinjam);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam</title>
</head>
<body>
    <h3>Form Peminjaman</h3>

    <p><b><?= $data['nama_barang']; ?></b></p>
    <p>Kategori: <?= $data['nama_kategori']; ?></p>
    <p>Jumlah Stok: <?= $data['jumlah']; ?></p>
    <p>Stok Dipinjam: <?= $data_pinjam['jumlah']; ?></p>

    <form method="POST">
        <input type="hidden" name="barang_id" value="<?= $data['id']; ?>">

        <label>Nama Peminjam</label>
        <input type="text" name="nama_peminjam" required>

        <label>Jumlah Pinjam</label>
        <input type="number" name="jumlah" min="1" max="<?= $data['jumlah']; ?>" required>

        <label>Keperluan</label>
        <textarea name="keperluan"></textarea>

        <button type="submit">Simpan Peminjaman</button>
    </form>
</body>
</html>
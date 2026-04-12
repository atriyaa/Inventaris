<?php
include "config/database.php";
 $lab = $_GET['lab'] ?? null;
 $filter = $_GET['filter'] ?? 'all';

 $where = [];

 if ($filter != 'all') {
    $where[] = "kategori = '$filter'";
 }
 if ($lab == 'lab_mm') {
    $where[] = "lokasi = 'LAB MM'";
 } elseif ($lab == 'lab_jarkom') {
    $where[] = "lokasi = 'LAB Jarkom'";
 }

 $where_sql = '';
 if (!empty($where)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where);
 }

$query = "
    SELECT barang.*, kategori.nama_kategori
    FROM barang
    JOIN kategori ON barang.kategori_id = kategori.id
    $where_sql 
    ORDER BY barang.id DESC
";
$result = mysqli_query($conn, $query)
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/style.css"
        <meta charset="UTF-8">
        <title>Inventaris Laboratorium Informatika</title>
    </head>
<body class="body">
    <div>
        <a href="login.php" class="btn btn-primary admin-login">
            🔐 Admin Login
        </a>
    </div>
    <div class="container">
        <div class="main" id="main">
        <div class="header">
            <div class="button_dash">
                <button id="toggleSidebar">☰</button>
                <h1>Dashboard</h1>
            </div>
        </div>
        <div class="kategori-select">
            <form method="GET">
                <select name="kategori" class="kategori-slct" id="kategoriSelect">
                    <option value="">Cari Kategori</option>
                    <option value="1">Alat Komputer</option>
                    <option value="2">Furniture</option>
                    <option value="3">Perangkat</option>
                    <option value="4">Elektronik</option>
                    <option value="5">Pendingin</option>
                </select>
            </form>
        </div>
        <div class="table-container">
            <table>
                <tr> 
                    <th>No</th>
                    <th>Kode Inventaris</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Tipe</th>
                    <th>Spesifikasi</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Tersedia</th>
                </tr>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['kode_inventaris']; ?></td>
                    <td><?= $row['nama_barang']; ?></td>
                    <td><?= $row['merk']; ?></td>
                    <td><?= $row['tipe']; ?></td>
                    <td><?= $row['spesifikasi']; ?></td>
                    <td><?= $row['jumlah']; ?></td>
                    <td><?= $row['keterangan']; ?></td>
                    <td><?= $row['tersedia'] == 1 ? 'Iya' : 'Tidak'; ?></td>
                    <?php } ?>
                </tr>
            </table>
        </div>
    </div>
<a href="index.php" class="text-decoration-none text-muted">
    ← Kembali ke Index
</a>
</body>
</html>
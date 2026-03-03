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
    $query = "SELECT * FROM barang $where_sql ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Inventaris Laboratorium Informatika</title>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<body>

<div class="d-flex justify-content-end mb-3">
    <a href="login.php" class="btn btn-primary admin-login">
        🔐 Admin Login
    </a>
</div>
<div class="container mt-4">
    <h2 class="text-center mb-4">Inventaris Laboratorium Informatika</h2>
    <div class="d-flex align-items-start gap-4">
        <div style="width: 250px">
                <a href="index.php?filter=Alat Komputer" class="card p-4 text-decoration-none text-dark">Alat Komputer</a>
                <a href="index.php?filter=Furniture" class="card p-4 text-decoration-none text-dark">Furniture</a>
                <a href="index.php?filter=Perangkat Audio" class="card p-4 text-decoration-none text-dark">Perangkat Audio</a>
                <a href="index.php?filter=Elektronik" class="card p-4 text-decoration-none text-dark">Elektronik</a>
                <a href="index.php?filter=Pendingin" class="card p-4 text-decoration-none text-dark">Pendingin</a>
        </div>
            <?php if ($lab): ?>
                <h3>Silahkan Pengajuan Peminjaman <?= $lab ?></h3>
            
                <form action="ajukan_lab.php" method="POST">
                    <input type="hidden" name="lab" value="<?= $lab ?>">
                    
                    <input type="text" name="nama" placeholder="Nama Peminjam" required>
                    <input type="text" name="keperluan" placeholder="Keperluan" required>
                    <input type="date" name="tanggal" required>
                    
                    <button class="btn btn-primary" type="submit">Ajukan Peminjaman</button>
                </form>
            
                <p><i>Status: menunggu persetujuan admin</i></p>
            
            <?php exit; // STOP, jangan tampilkan tabel barang ?>
            <?php endif; ?>
        <div style="flex:1">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Jumlah</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
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
                    <td><?= $row['jumlah']; ?></td>
                    <td><?= $row['lokasi']; ?></td>
                    <td>
                        <?php if ($row['jumlah'] > 0) { ?>
                        <form action="pinjam.php" method="POST">
                            <input type="hidden" name="barang_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="jumlah" value="1">
                            <input type="hidden" name="nama" value="User">
                            <input type="hidden" name="keperluan" value="Peminjaman">
                            <button type="submit">Pinjam</button>
                        </form>
                        <?php } else { ?>
                        Habis
                        <?php } ?>
                        <?php } ?>
                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<a href="index.php" class="text-decoration-none text-muted">
    ← Kembali ke Index
</a>
</body>
</html>
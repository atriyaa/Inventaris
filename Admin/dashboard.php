<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $pesan = '';
    if (isset($_GET['pinjam']) && $_GET['pinjam'] === 'success') {
        $pesan = "Peminjaman berhasil 👍";
    }
    if (!isset($_SESSION['admin'])) {
        header("Location: ../login.php");
        exit;
        }
        $lab = $_GET['lab'] ?? null;
        $filter = $_GET['filter'] ?? 'all';

    $where = [];

    if ($filter != 'all') {
        $where[] = "barang.kategori_id = '$filter'";
    }

    if ($lab == 'lab_mm') {
        $where[] = "barang.lokasi = 'LAB MM'";
    } elseif ($lab == 'lab_jarkom') {
        $where[] = "barang.lokasi = 'LAB Jarkom'";
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
    <link rel="stylesheet" href="<?= '../assets/style.css'; ?>">
    <meta charset="UTF-8">
    <title>Inventaris Laboratorium Informatika</title>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<body>
    <div><a href="export_excel.php" class="">
        <button>Download Excel</button>
    </a>
    </div>
    <div>
        <a href="../pinjam.php">Pinjam</a>
    </div>
    <h2 class="text-center mb-4">Inventaris Laboratorium Informatika</h2>
        <div class="category-container">
            <a href="dashboard.php?filter=1" class="category-card"><span>Alat Komputer</span></a>
            <a href="dashboard.php?filter=2" class="category-card""><span>Furniture</span></a>
            <a href="dashboard.php?filter=3" class="category-card""><span>Perangkat Audio</span></a>
            <a href="dashboard.php?filter=4" class="category-card""><span>Elektronik</span></a>
            <a href="dashboard.php?filter=5" class="category-card""><span>Pendingin</span></a>
        </div>
    <div class="container mt-4">
        <div class="d-flex align-items-start gap-4">
            <div style="flex:1"> 
                <table class="table table-bordered table-striped">
                    <tr> 
                        <th>No</th>
                        <th>Kode Inventaris</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Tipe</th>
                        <th>Spesifikasi</th>
                        <th>Jumlah</th>
                        <th>Kondisi</th>
                        <th>Lokasi</th>
                        <th>Tahun Perolehan</th>
                        <th>Keterangan</th>
                        <th>Tersedia</th>
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
                        <td><?= $row['tipe']; ?></td>
                        <td><?= $row['spesifikasi']; ?></td>
                        <td><?= $row['jumlah']; ?></td>
                        <td><?= $row['kondisi']; ?></td>
                        <td><?= $row['lokasi']; ?></td>
                        <td><?= $row['tahun_perolehan']; ?></td>
                        <td><?= $row['keterangan']; ?></td>
                        <td><?= $row['tersedia'] == 1 ? 'Iya' : 'Tidak'; ?></td>
                        <td>
                            <a href="edit.php?id=<?=  $row['id'];?>">Edit</a> |
                            <a href="delete.php?id=<?=  $row['id'];?>"
                                onclick="return confirm('Yakin mau hapus data ini?')"> 
                                Delete
                            </a>
                        </td>
                    <?php } ?>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<section class="action-buttons">
    <a href="dashboard.php" class="btn-dashboard">Back to Dashboard</a>
    <a href="../index.php" class="btn-index">Back to Index</a>
    <a href="create.php" class="btn-submit">Tambah Data</a>
</section>
</body>
</html>

<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    // if (!isset($_SESSION['admin'])) {
    //     header("Location: ../login.php");
    //     exit;
    // }
    $lab = $_GET['lab'] ?? null;
    $filter = $_GET['filter'] ?? 'all';
    $filter = mysqli_real_escape_string($conn, $filter);
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

    $limit = 15;
    $halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
    if ($halaman_aktif <= 0) $halaman_aktif = 1;
    $offset = ($halaman_aktif - 1) * $limit;

    // Hitung total data untuk tahu jumlah halaman
    $query_total = "SELECT COUNT(*) AS total FROM barang JOIN kategori ON barang.kategori_id = kategori.id $where_sql";
    $result_total = mysqli_query($conn, $query_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_data = $row_total['total'];
    $total_halaman = ceil($total_data / $limit);

    $query = "
        SELECT barang.*, kategori.nama_kategori
        FROM barang
        JOIN kategori ON barang.kategori_id = kategori.id
        $where_sql 
        ORDER BY barang.id DESC
        LIMIT $limit OFFSET $offset
    ";
    $result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Laboratorium Informatika</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style2.css">
</head>
<body>
    <?php $page = basename($_SERVER['PHP_SELF']); ?>
    <?php include "../include/sidebar.php" ?>

    <main>
        <header>
            <i class="fa fa-bars" id="toggle-btn"></i>
            <div style="font-size: 14px;">
                Ahmad Jhony - administrator &nbsp; <i class="fa fa-sign-out"><a href="logout.php" style="color: #000000;"> LOGOUT</a></i> 
            </div>
        </header>

        <div class="breadcrumb">
            <h2 style="font-size: 18px; color: #333;">Inventaris <small style="color: #999; font-weight: 300;">Data Barang</small></h2>
            <div><i class="fa fa-home"></i> <a href="logout.php">Home</a> <a href="inventaris.php"> > Inventaris</a></div>
        </div>

        <div class="content-wrapper">
            <div class="card">
                <?php if (isset($_GET['pesan'])): ?>
                    <div class="alert-container">
                        <?php if ($_GET['pesan'] == 'hapus'): ?>
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> Data barang berhasil <strong>dihapus</strong>!
                            </div>
                        <?php elseif ($_GET['pesan'] == 'edit'): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Data barang berhasil <strong>diperbarui</strong>!
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="card-header">
                    <div class="kategori-box">
                        <form method="GET">
                            <select name="filter" id="kategoriSelect" onchange="this.form.submit()">
                                <option value="">Filter Kategori</option>
                                <option value="1">Alat Komputer</option>
                                <option value="2">Furniture</option>
                                <option value="3">Perangkat Audio</option>
                                <option value="4">Elektronik</option>
                                <option value="5">Pendingin</option>
                            </select>
                        </form>
                    </div>
                    <button class="btn-tambah"> <a href="create.php">+ Tambah Barang</a></button>
                    <button class="btn-tambah"> <a href="export_excel.php"> > Export Excel</a></button>
                </div>

                <table>
                    <thead>
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
                    </thead>
                        <?php
                        $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                    <tbody>
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
                                <a class="btn-action btn-edit" href="edit.php?id=<?= $row['id']; ?>"><i class="fa fa-cog"></i></a>
                                <a class="btn-action btn-delete" href=delete.php?id=<?= $row['id']; ?>"><i class="fa fa-trash"></i></a>
                            </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>

                <div class="table-footer">
                    <p>Showing <?= ($offset + 1); ?> to <?= min($offset + $limit, $total_data); ?> of <?= $total_data; ?> entries</p>
                    <ul class="pagination">
                        <?php if($halaman_aktif > 1): ?>
                            <li><a href="?halaman=<?= $halaman_aktif - 1; ?>&lab=<?= $lab; ?>&filter=<?= $filter; ?>">Previous</a></li>
                        <?php else: ?>
                            <li class="disabled">Previous</li>
                        <?php endif; ?>

                        <li class="active"><?= $halaman_aktif; ?></li>

                        <?php if($halaman_aktif < $total_halaman): ?>
                            <li><a href="?halaman=<?= $halaman_aktif + 1; ?>&lab=<?= $lab; ?>&filter=<?= $filter; ?>">Next</a></li>
                        <?php else: ?>
                            <li class="disabled">Next</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
<script>
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.querySelector('aside');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

    const alertBox = document.querySelector('.alert-container');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000); // 3000ms = 3 detik
    }
</script>
</body>
</html>
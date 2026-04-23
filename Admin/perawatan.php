<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();

    $limit = 15;
    $halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
    if ($halaman_aktif <= 0) $halaman_aktif = 1;
    $offset = ($halaman_aktif - 1) * $limit;

    // 1. Definisikan variabel awal agar tidak "Undefined"
    $where_sql = ''; 
    $where = [];

    // 2. Logika filter (ambil dari kode sebelumnya)
    $lab = $_GET['lab'] ?? null;
    $filter = $_GET['filter'] ?? 'all';

    if ($filter != 'all') {
    $where[] = "barang.kategori_id = '$filter'";
    }

    if ($lab == 'lab_mm') {
    $where[] = "barang.lokasi = 'LAB MM'";
    } elseif ($lab == 'lab_jarkom') {
    $where[] = "barang.lokasi = 'LAB Jarkom'";
    }

    // 3. Gabungkan array menjadi string WHERE
    if (!empty($where)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where);
    }

    // Hitung total data untuk tahu jumlah halaman
    $query_total = "SELECT COUNT(*) AS total 
                    FROM perawatan
                    JOIN barang ON perawatan.id = barang.id 
                    $where_sql";
    $result_total = mysqli_query($conn, $query_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_data = $row_total['total'];
    $total_halaman = ceil($total_data / $limit);

$where_sql = "WHERE peminjaman.status = 'dikembalikan'";
$query = mysqli_query($conn,"
    SELECT tanggal_perbaikan, deskripsi, nama_barang
    FROM perawatan
    inner join barang on perawatan.id = barang.id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>perawatan</title>
    <link rel="stylesheet" href="/assets/style2.css">
</head>
<body>
    <?php $page = basename($_SERVER['PHP_SELF']); ?>
    <aside>
        <div class="user-panel">
            <img src="image/rooney.jpg" alt="User">
            <div>
                <p style="color: white; font-weight: 600;">Ahmad Jhony</p>
                <small style="color: #2ecc71;"><i class="fa fa-circle"></i> Online</small>
            </div>
        </div>
        
        <?php include '../include/menu.php'; ?>
    </aside>

    <main>
        <header>
            <i class="fa fa-bars" id="toggle-btn"></i>
            <div style="font-size: 14px;">
                Ahmad Jhony - administrator &nbsp; <i class="fa fa-sign-out"><a href="logout.php" style="color: #000000;"> LOGOUT</a></i> 
            </div>
        </header>

        <div class="breadcrumb">
            <h2 style="font-size: 18px; color: #333;">Perawatan <small style="color: #999; font-weight: 300;">History Data perawatan</small></h2>
            <div><i class="fa fa-home"></i> <a href="logout.php">Home</a> > <a href="dashboard.php">Dashboard</a></div>
        </div>

        <div class="content-wrapper">
            <div class="card">
                <?php if (isset($_GET['create']) && $_GET['create'] == 'success'): ?>
                <div id='notif' style="background: green; color: white; padding: 10px;">
                    Data berhasil ditambahkan!
                </div>
                <?php endif; ?>
                <div class="card-header">
                    <button class="btn-tambah" ><a href="form_perawatan.php">+ Tambah Perawatan</a></button>
                    <button class="btn-tambah"> <a href="export_perawatan.php"> > Export Excel</a></button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Tanggal Perawatan</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <?php
                    if ($query->num_rows> 0) {
                        $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($query)) {
                    ?>
                    <tbody>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nama_barang']; ?></td>
                            <td><?= date("d F Y", strtotime($row['tanggal_perbaikan'])); ?></td>
                            <td><?= $row['deskripsi']; ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
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
    setTimeout(function() {
        let notif = document.getElementById("notif");
        if (notif) {
            notif.style.display = "none";
        }
    }, 3000); // 3000 ms = 3 detik
</script>
</body>
</html>
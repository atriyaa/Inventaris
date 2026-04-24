<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();

    $limit = 10;
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

$query = mysqli_query($conn,"
    SELECT kode_inventaris, tanggal_perbaikan, deskripsi, nama_barang
    FROM perawatan
    inner join barang on perawatan.id = barang.id ORDER BY tanggal_perbaikan DESC
    LIMIT $limit OFFSET $offset
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>InventarisApp Dashboard</title>
    <style>
        /* Transisi halus */
        aside { transition: width 0.3s ease; }
        
        /* Gaya saat sidebar disembunyikan (collapsed) */
        .collapsed {
            width: 0 !important;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <?php include '../include/menu.php'; ?>
        <div class="flex-1 flex flex-col overflow-y-auto">
            <?php include '../include/header_hlm.php'; ?>
            <main class="p-6">
                <div class="bg-white border-t-4 border-blue-400 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b flex items-center justify-between bg-white w-full">
                        <h3 class="font-semibold text-gray-700 text-lg">Perawatan Barang</h3>
                        <div class="px-5 py-3 border-b flex items-center justify-end bg-white w-full flex gap-4">
                            <div class="flex-shrink-0">
                                <a href="form_perawatan.php" class="inline-flex items-center bg-[#3c8dbc] hover:bg-[#367fa9] text-white text-xs font-bold px-3 py-2 rounded shadow-sm transition-all uppercase tracking-wider">
                                    <i class="fas fa-plus mr-2"></i> Tambah Perawatan
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="export_perawatan.php" class="inline-flex items-center bg-[#3c8dbc] hover:bg-[#367fa9] text-white text-xs font-bold px-3 py-2 rounded shadow-sm transition-all uppercase tracking-wider">
                                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b text-gray-700">
                                    <th class="p-3 font-bold uppercase text-xs">No</th>
                                    <th class="p-3 font-bold uppercase text-xs">Kode Inventaris</th>
                                    <th class="p-3 font-bold uppercase text-xs">Nama Barang</th>
                                    <th class="p-3 font-bold uppercase text-xs">Tanggal perbaikan</th>
                                    <th class="p-3 font-bold uppercase text-xs">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php
                                $no = $offset + 1;
                                while ($row = mysqli_fetch_assoc($query)) {
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-3 text-center"><?= $no++; ?></td>
                                    <td class="p-3 font-mono text-blue-600"><?= $row['kode_inventaris']; ?></td>
                                    <td class="p-3 font-mono text-blue-600"><?= $row['nama_barang']; ?></td>
                                    <td class="p-3 font-medium"><?= $row['tanggal_perbaikan']; ?></td>
                                    <td class="p-3 text-xs text-gray-600"><?= $row['deskripsi']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                            <p class="text-sm text-gray-700">Showing <span class="font-semibold"><?= ($offset + 1); ?></span> to <span class="font-semibold"><?= min($offset + $limit, $total_data); ?></span> of <span class="font-semibold"> <?= $total_data; ?></span> entries </p>
                            <ul class="flex items-center -space-x-px shadow-sm rounded-md text-sm font-medium">
                                <?php if($halaman_aktif > 1): ?>
                                    <li class="inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 transition"><a href="?halaman=<?= $halaman_aktif - 1; ?>&lab=<?= $lab; ?>&filter=<?= $filter; ?>">Previous</a></li>
                                <?php else: ?>
                                    <li class="inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">Previous</li>
                                <?php endif; ?>

                                <li class="z-10 bg-blue-600 border-blue-600 text-white inline-flex items-center px-4 py-2 border font-bold"><?= $halaman_aktif; ?></li>

                                <?php if($halaman_aktif < $total_halaman): ?>
                                    <li class="inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 transition"><a href="?halaman=<?= $halaman_aktif + 1; ?>&lab=<?= $lab; ?>&filter=<?= $filter; ?>">Next</a></li>
                                <?php else: ?>
                                    <li class="inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">Next</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<!-- <body>
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
</body> -->
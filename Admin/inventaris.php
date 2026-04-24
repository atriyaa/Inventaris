<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
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
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-light">Dashboard <span class="text-sm text-gray-500">Control panel</span></h1>
                    <nav class="text-xs text-gray-500"><i class="fas fa-home"></i> Home > Dashboard</nav>
                </div>
                <div class="bg-white border-t-4 border-blue-400 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b flex items-center justify-between bg-white w-full">
                        <h3 class="font-semibold text-gray-700 text-lg">Barang</h3>
                        <div class="px-5 py-3 border-b flex items-center justify-end bg-white w-full">
                            <div class="flex-shrink-0">
                                <a href="tambah_data.php" class="inline-flex items-center bg-[#3c8dbc] hover:bg-[#367fa9] text-white text-xs font-bold px-3 py-2 rounded shadow-sm transition-all uppercase tracking-wider">
                                    <i class="fas fa-plus mr-2"></i> Tambah Barang
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="export_inventaris.php" class="inline-flex items-center bg-[#3c8dbc] hover:bg-[#367fa9] text-white text-xs font-bold px-3 py-2 rounded shadow-sm transition-all uppercase tracking-wider">
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
                                    <th class="p-3 font-bold uppercase text-xs">Merk</th>
                                    <th class="p-3 font-bold uppercase text-xs">Tipe</th>
                                    <th class="p-3 font-bold uppercase text-xs">Spesifikasi</th>
                                    <th class="p-3 font-bold uppercase text-xs">Jumlah</th>
                                    <th class="p-3 font-bold uppercase text-xs">Kondisi</th>
                                    <th class="p-3 font-bold uppercase text-xs">Lokasi</th>
                                    <th class="p-3 font-bold uppercase text-xs">Tahun Perolehan</th>
                                    <th class="p-3 font-bold uppercase text-xs">Keterangan</th>
                                    <th class="p-3 font-bold uppercase text-xs">Tersedia</th>
                                    <th class="p-3 font-bold uppercase text-xs">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php
                                $no = $offset + 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-3 text-center"><?= $no++; ?></td>
                                    <td class="p-3 font-mono text-blue-600"><?= $row['kode_inventaris']; ?></td>
                                    <td class="p-3 font-medium"><?= $row['nama_barang']; ?></td>
                                    <td class="p-3"><?= $row['merk']; ?></td>
                                    <td class="p-3"><?= $row['tipe']; ?></td>
                                    <td class="p-3 text-xs text-gray-600"><?= $row['spesifikasi']; ?></td>
                                    <td class="p-3 text-center">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">
                                            <?= $row['jumlah']; ?>
                                        </span>
                                    </td>
                                    <td class="p-3"><?= $row['kondisi']; ?></td>
                                    <td class="p-3"><?= $row['lokasi']; ?></td>
                                    <td class="p-3"><?= $row['tahun_perolehan']; ?></td>
                                    <td class="p-3"><?= $row['keterangan']; ?></td>
                                    <td class="p-3"><?= $row['tersedia']; ?></td>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggle-btn');
        const sidebar = document.querySelector('aside');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        }

        // Script Alert
        const alertBox = document.querySelector('.alert-container');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.opacity = '0'; // Biar halus
                setTimeout(() => alertBox.style.display = 'none', 500);
            }, 3000);
        }
    });
</script>
</body>
</html>
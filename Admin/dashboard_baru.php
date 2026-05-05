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

    // $limit = 15;
    // $halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
    // if ($halaman_aktif <= 0) $halaman_aktif = 1;
    // $offset = ($halaman_aktif - 1) * $limit;

    // // Hitung total data untuk tahu jumlah halaman
    // $query_total = "SELECT COUNT(*) AS total FROM barang_detail INNER JOIN  ON barang.kategori_id = kategori.id $where_sql";
    // $result_total = mysqli_query($conn, $query_total);
    // $row_total = mysqli_fetch_assoc($result_total);
    // $total_data = $row_total['total'];
    // $total_halaman = ceil($total_data / $limit);

    // Perbaikan query count
    // Perbaikan query count dengan pengecekan hasil
    $count_admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_admin FROM admin"))['total_admin'];
    $count_barang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_barang FROM barang"))['total_barang'];
    $count_kategori1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_barang FROM barang_detail INNER JOIN barang on barang_detail.id_barang = barang.id_barang WHERE id_kategori=1"))['total_barang'];
    $count_license = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total_license FROM  software_license "))['total_liccense'];
    $count_kategori = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total_kategori FROM kategori"))['total_kategori'];
    $count_peminjaman_aktif = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total_peminjaman FROM peminjaman"))['total_peminjaman'];
    $count_kerusakan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total_kerusakan FROM kerusakan WHERE status_perbaikan='Menunggu'"))['total_kerusakan'];
    $count_perawatan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total_perawatan FROM perawatan"))['total_perawatan'];



$cards = [
    ['judul' => 'Data Admin', 'jumlah' => $count_admin, 'icon' => 'fa-box', 'bg' => 'bg-green-500', 'link' => 'inventaris.php'],
    ['judul' => 'Total Barang', 'jumlah' => $count_barang, 'icon' => 'fa-users', 'bg' => 'bg-yellow-500', 'link' => 'dashboard_baru.php'],
    ['judul' => 'Kategori', 'jumlah' => $count_kategori, 'icon' => 'fa-tags', 'bg' => 'bg-blue-500', 'link' => 'kategori.php'],
    ['judul' => 'Peminjaman Aktif', 'jumlah' => $count_peminjaman_aktif, 'icon' => 'fa-exchange-alt', 'bg' => 'bg-blue-400', 'link' => 'peminjaman.php'],
    ['judul' => 'Barang Rusak', 'jumlah' => $count_kerusakan, 'icon' => 'fa-exclamation-triangle', 'bg' => 'bg-red-500', 'link' => 'kerusakan.php'],
    ['judul' => 'Dalam Perawatan', 'jumlah' => $count_perawatan, 'icon' => 'fa-wrench', 'bg' => 'bg-orange-500', 'link' => 'perawatan.php'],
    ['judul' => 'Total License', 'jumlah' => $count_license, 'icon' => 'fa-key', 'bg' => 'bg-purple-600', 'link' => 'license.php'],
    // ['judul' => 'Kategori Alat Komputer', 'jumlah' => $count_kategori_1, 'icon' => 'fa-folder', 'bg' => 'bg-teal-500', 'link' => 'kategori.php'],
    // ['judul' => 'Kategori Furniture', 'jumlah' => $count_kategori_2, 'icon' => 'fa-folder-open', 'bg' => 'bg-cyan-500', 'link' => 'kategori.php'],
    // ['judul' => 'Kategori Perangkat Audio', 'jumlah' => $count_kategori_3, 'icon' => 'fa-file-alt', 'bg' => 'bg-sky-500', 'link' => 'kategori.php'],
    // ['judul' => 'Kategori Elektronik', 'jumlah' => $count_kategori_4, 'icon' => 'fa-archive', 'bg' => 'bg-blue-600', 'link' => 'kategori.php'],
    // ['judul' => 'Kategori Pendingin', 'jumlah' => $count_kategori_5, 'icon' => 'fa-layer-group', 'bg' => 'bg-indigo-600', 'link' => 'kategori.php'],
    // ['judul' => 'Pengembalian', 'jumlah' => $count_pengembalian, 'icon' => 'fa-check-circle', 'bg' => 'bg-emerald-500', 'link' => 'peminjaman.php'],
    // ['judul' => 'History Pinjam', 'jumlah' => $count_history_peminjaman, 'icon' => 'fa-history', 'bg' => 'bg-slate-500', 'link' => 'peminjaman.php'],
];
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
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-light">Dashboard</h1>
                    <nav class="text-xs text-gray-500"><i class="fas fa-home"></i> <a href="../index.php">Home</a> > <a href="dashboard_baru.php">Dashboard</a></nav>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <?php foreach ($cards as $card) : ?>
                    <div class="<?= $card['bg']; ?> text-white rounded shadow-md flex flex-col transition-transform hover:scale-[1.02] duration-200">
                        <div class="p-4 flex justify-between items-fastart relative overflow-hidden">
                            <div class="z-10">
                                <h3 class="text-3xl font-bold"><?= $card['jumlah']; ?></h3>
                                <p class="text-sm font-medium opacity-90"><?= $card['judul']; ?></p>
                            </div>
                            <div class="absolute -right-2 -bottom-2 opacity-20 transition-transform duration-500 group-hover:scale-110">
                                <i class="fas <?= $card['icon']; ?> text-6xl"></i>
                            </div>
                        </div>
                        <a href="<?= $card['link']; ?>" class="bg-black/10 py-1.5 text-center text-xs font-semibold hover:bg-black/20 transition-colors flex items-center justify-center">
                            More info <i class="fas fa-arrow-circle-right ml-1"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>
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
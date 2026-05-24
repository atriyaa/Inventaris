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
        $where_sql = 'WHERE ' . implode(' AND  ', $where);
    }

    
    // Query untuk kategori-kategori spesifik
    $count_barang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM barang"))['total'] ?? 0;
    $count_kategori_all = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id_kategori) as total FROM kategori"))['total'] ?? 0;

    // Mengambil semua hitungan kategori dalam satu query agar lebih cepat
    $cat_query = mysqli_query($conn, "SELECT id_kategori, COUNT(*) as jumlah FROM barang GROUP BY id_kategori");
    $cat_counts = [];
    while($row = mysqli_fetch_assoc($cat_query)) {
        $cat_counts[$row['id_kategori']] = $row['jumlah'];
    }

    // Data Peminjaman & History
    $count_peminjaman_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status_pinjam='proses'"))['total'] ?? 0;
    $count_history_peminjaman = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman"))['total'] ?? 0;
    $count_license = mysqli_num_rows(mysqli_query($conn, "SELECT id_license FROM software_license"));

    // Masukkan ke dalam Array Cards
    $cards = [
        ['judul' => 'Total Barang', 'jumlah' => $count_barang, 'icon' => 'fa-box', 'bg' => 'bg-blue-600', 'link' => 'inventaris.php'],
        ['judul' => 'Total Software', 'jumlah' => $count_license, 'icon' => 'fa-code', 'bg' => 'bg-purple-600', 'link' => 'license.php'],
        ['judul' => 'Kategori Utama', 'jumlah' => $count_kategori_all, 'icon' => 'fa-tags', 'bg' => 'bg-gray-700', 'link' => 'kategori.php'],
        ['judul' => 'Alat Komputer', 'jumlah' => $cat_counts[1] ?? 0, 'icon' => 'fa-laptop', 'bg' => 'bg-teal-500', 'link' => 'kategori.php?id=1'],
        ['judul' => 'Furniture', 'jumlah' => $cat_counts[2] ?? 0, 'icon' => 'fa-chair', 'bg' => 'bg-orange-500', 'link' => 'kategori.php?id=2'],
        ['judul' => 'Alat Audio', 'jumlah' => $cat_counts[3] ?? 0, 'icon' => 'fa-volume-up', 'bg' => 'bg-pink-500', 'link' => 'kategori.php?id=3'],
        ['judul' => 'Elektronik', 'jumlah' => $cat_counts[4] ?? 0, 'icon' => 'fa-plug', 'bg' => 'bg-cyan-600', 'link' => 'kategori.php?id=4'],
        ['judul' => 'Pendingin', 'jumlah' => $cat_counts[5] ?? 0, 'icon' => 'fa-snowflake', 'bg' => 'bg-sky-400', 'link' => 'kategori.php?id=5'],
        ['judul' => 'Peminjaman Aktif', 'jumlah' => $count_peminjaman_aktif, 'icon' => 'fa-exchange-alt', 'bg' => 'bg-emerald-500', 'link' => 'peminjaman.php'],
        ['judul' => 'History Pinjam', 'jumlah' => $count_history_peminjaman, 'icon' => 'fa-history', 'bg' => 'bg-slate-600', 'link' => 'history.php'],
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
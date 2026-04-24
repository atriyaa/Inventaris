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
    $count_kategori_1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(b.jumlah) as total_barang FROM barang b INNER JOIN kategori k ON b.kategori_id = k.id WHERE nama_kategori='alat_komputer';"))['total_barang'];
    $count_kategori_2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(b.jumlah) as total_barang FROM barang b INNER JOIN kategori k ON b.kategori_id = k.id WHERE nama_kategori='furniture';"))['total_barang'];
    $count_kategori_3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(b.jumlah) as total_barang FROM barang b INNER JOIN kategori k ON b.kategori_id = k.id WHERE nama_kategori='perangkat_audio';"))['total_barang'];
    $count_kategori_4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(b.jumlah) as total_barang FROM barang b INNER JOIN kategori k ON b.kategori_id = k.id WHERE nama_kategori='elektronik';"))['total_barang'];
    $count_kategori_5 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(b.jumlah) as total_barang FROM barang b INNER JOIN kategori k ON b.kategori_id = k.id WHERE nama_kategori='pendingin';"))['total_barang'];

    $cards = [
    ['judul' => 'Kategori Alat Komputer', 'jumlah' => $count_kategori_1, 'icon' => 'fa-folder', 'bg' => 'bg-teal-500', 'link' => 'inventaris.php?filter1'],
    ['judul' => 'Kategori Furniture', 'jumlah' => $count_kategori_2, 'icon' => 'fa-folder-open', 'bg' => 'bg-cyan-500', 'link' => 'inventaris.php?filter2'],
    ['judul' => 'Kategori Perangkat Audio', 'jumlah' => $count_kategori_3, 'icon' => 'fa-file-alt', 'bg' => 'bg-sky-500', 'link' => 'inventaris.php?filter3'],
    ['judul' => 'Kategori Elektronik', 'jumlah' => $count_kategori_4, 'icon' => 'fa-archive', 'bg' => 'bg-blue-600', 'link' => 'inventaris.php?filter4'],
    ['judul' => 'Kategori Pendingin', 'jumlah' => $count_kategori_5, 'icon' => 'fa-layer-group', 'bg' => 'bg-indigo-600', 'link' => 'inventaris.php?filter5']
    ]
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
<body class="gb-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <?php include '../include/menu.php'; ?>
        <div class="flex-1 flex flex-col overflow-y-auto">
            <?php include '../include/header_hlm.php'; ?>
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2x font-light">Dashboard </h1>
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
</body>
</html>
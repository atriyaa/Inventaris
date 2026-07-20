<?php
require_once __DIR__ . "/../config/database.php";
session_start();

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

$limit = 10;

$halaman_aktif = isset($_GET['halaman'])
    ? (int)$_GET['halaman']
    : 1;

if ($halaman_aktif <= 0) {
    $halaman_aktif = 1;
}

$offset = ($halaman_aktif - 1) * $limit;

/*
|--------------------------------------------------------------------------
| FILTER STATUS
|--------------------------------------------------------------------------
*/

$status = $_GET['status'] ?? 'all';

$where = [];

if ($status != 'all') {

    if ($status == 'proses') {
        $where[] = "peminjaman.status_pinjam = 'Proses'";
    }

    if ($status == 'selesai') {
        $where[] = "peminjaman.status_pinjam = 'Selesai'";
    }
}

$where_sql = '';

if (!empty($where)) {
    $where_sql = "WHERE " . implode(' AND ', $where);
}

/*
|--------------------------------------------------------------------------
| TOTAL DATA
|--------------------------------------------------------------------------
*/

$query_total = "
    SELECT COUNT(*) AS total
    FROM peminjaman
    $where_sql
";

$result_total = mysqli_query($conn, $query_total);

$row_total = mysqli_fetch_assoc($result_total);

$total_data = $row_total['total'];

$total_halaman = ceil($total_data / $limit);

/*
|--------------------------------------------------------------------------
| QUERY DATA PEMINJAMAN
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn, "
    SELECT 
        peminjaman.id_peminjaman,
        peminjaman.nama_peminjam,
        peminjaman.tanggal_pinjam,
        peminjaman.tanggal_kembali,
        peminjaman.status_pinjam,

        COUNT(peminjaman_detail.id_pinjam_detail) 
        AS total_barang

    FROM peminjaman

    LEFT JOIN peminjaman_detail
        ON peminjaman.id_peminjaman = peminjaman_detail.id_peminjaman

    $where_sql

    GROUP BY peminjaman.id_peminjaman

    ORDER BY peminjaman.id_peminjaman DESC

    LIMIT $limit OFFSET $offset
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Peminjaman</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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

<body class="bg-gray-100 min-h-screen">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <?php include '../include/menu.php'; ?>

        <!-- CONTENT -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- HEADER -->
            <?php include '../include/header_hlm.php'; ?>

            <!-- MAIN -->
            <main class="p-6">

                <!-- PAGE HEADER -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">
                            Data Peminjaman
                        </h1>

                        <p class="text-gray-500 mt-1">
                            Kelola seluruh transaksi peminjaman barang laboratorium
                        </p>
                    </div>

                    <div class="flex gap-3">

                        <!-- FILTER -->
                        <form method="GET">

                            <select
                                name="status"
                                onchange="this.form.submit()"
                                class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                                <option value="all"
                                    <?= $status == 'all' ? 'selected' : ''; ?>>
                                    Semua Status
                                </option>

                                <option value="proses"
                                    <?= $status == 'proses' ? 'selected' : ''; ?>>
                                    Proses
                                </option>

                                <option value="selesai"
                                    <?= $status == 'selesai' ? 'selected' : ''; ?>>
                                    Selesai
                                </option>

                            </select>

                        </form>

                        <!-- BUTTON -->
                        <a href="tambah_peminjaman.php"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-sm font-medium shadow transition">

                            <i class="fa-solid fa-plus"></i>

                            Tambah Peminjaman

                        </a>

                    </div>

                </div>

                <!-- CARD -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                    <!-- TABLE HEADER -->
                    <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">

                        <div>

                            <h2 class="text-lg font-semibold text-gray-800">
                                Histori Peminjaman
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Total <?= $total_data; ?> transaksi ditemukan
                            </p>

                        </div>

                    </div>

                    <!-- TABLE -->
                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600">

                                <tr>

                                    <th class="px-6 py-4 text-left font-semibold">
                                        No
                                    </th>

                                    <th class="px-6 py-4 text-left font-semibold">
                                        Nama Peminjam
                                    </th>

                                    <th class="px-6 py-4 text-left font-semibold">
                                        Tanggal Pinjam
                                    </th>

                                    <th class="px-6 py-4 text-left font-semibold">
                                        Tanggal Kembali
                                    </th>

                                    <th class="px-6 py-4 text-center font-semibold">
                                        Total Barang
                                    </th>

                                    <th class="px-6 py-4 text-center font-semibold">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-center font-semibold">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                <?php if (mysqli_num_rows($query) > 0): ?>

                                    <?php $no = $offset + 1; ?>

                                    <?php while ($row = mysqli_fetch_assoc($query)): ?>

                                        <tr class="hover:bg-gray-50 transition">

                                            <!-- NO -->
                                            <td class="px-6 py-4 text-gray-700">
                                                <?= $no++; ?>
                                            </td>

                                            <!-- NAMA -->
                                            <td class="px-6 py-4">

                                                <div class="font-semibold text-gray-800">
                                                    <?= htmlspecialchars($row['nama_peminjam']); ?>
                                                </div>

                                            </td>

                                            <!-- TANGGAL PINJAM -->
                                            <td class="px-6 py-4 text-gray-600">

                                                <?= date('d M Y', strtotime($row['tanggal_pinjam'])); ?>

                                            </td>

                                            <!-- TANGGAL KEMBALI -->
                                            <td class="px-6 py-4 text-gray-600">

                                                <?= date('d M Y', strtotime($row['tanggal_kembali'])); ?>

                                            </td>

                                            <!-- TOTAL BARANG -->
                                            <td class="px-6 py-4 text-center">

                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">

                                                    <?= $row['total_barang']; ?> Barang

                                                </span>

                                            </td>

                                            <!-- STATUS -->
                                            <td class="px-6 py-4 text-center">

                                                <?php if ($row['status_pinjam'] == 'Proses'): ?>

                                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">

                                                        <i class="fa-solid fa-clock"></i>

                                                        Proses

                                                    </span>

                                                <?php else: ?>

                                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">

                                                        <i class="fa-solid fa-check"></i>

                                                        Selesai

                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <!-- AKSI -->
                                            <td class="px-6 py-4">

                                                <div class="flex items-center justify-center gap-2">

                                                    <!-- DETAIL -->
                                                    <a href="detail_peminjaman.php?id=<?= $row['id_peminjaman']; ?>"
                                                        class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 flex items-center justify-center transition"
                                                        title="Detail">

                                                        <i class="fa-solid fa-eye"></i>

                                                    </a>

                                                    <!-- KEMBALIKAN -->
                                                    <?php if ($row['status_pinjam'] == 'Proses'): ?>

                                                        <a href="kembalikan.php?id=<?= $row['id_peminjaman']; ?>"
                                                            onclick="return confirm('Yakin ingin menyelesaikan peminjaman ini?')"
                                                            class="w-10 h-10 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 flex items-center justify-center transition"
                                                            title="Kembalikan">

                                                            <i class="fa-solid fa-rotate-left"></i>

                                                        </a>

                                                    <?php endif; ?>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>

                                        <td colspan="7" class="px-6 py-12 text-center">

                                            <div class="flex flex-col items-center">

                                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">

                                                    <i class="fa-solid fa-box-open text-2xl text-gray-400"></i>

                                                </div>

                                                <h3 class="text-lg font-semibold text-gray-700 mb-1">
                                                    Data tidak ditemukan
                                                </h3>

                                                <p class="text-sm text-gray-500">
                                                    Belum ada transaksi peminjaman
                                                </p>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                    <!-- PAGINATION -->
                    <div class="px-6 py-4 border-t border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- INFO -->
                        <div class="text-sm text-gray-600">

                            Menampilkan

                            <span class="font-semibold">
                                <?= ($offset + 1); ?>
                            </span>

                            sampai

                            <span class="font-semibold">
                                <?= min($offset + $limit, $total_data); ?>
                            </span>

                            dari

                            <span class="font-semibold">
                                <?= $total_data; ?>
                            </span>

                            data

                        </div>

                        <!-- BUTTON -->
                        <div class="flex items-center gap-2">

                            <!-- PREV -->
                            <?php if ($halaman_aktif > 1): ?>

                                <a href="?halaman=<?= $halaman_aktif - 1; ?>&status=<?= $status; ?>"
                                    class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-sm">

                                    Previous

                                </a>

                            <?php else: ?>

                                <button
                                    class="px-4 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-400 text-sm cursor-not-allowed">

                                    Previous

                                </button>

                            <?php endif; ?>

                            <!-- PAGE -->
                            <div class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold">

                                <?= $halaman_aktif; ?>

                            </div>

                            <!-- NEXT -->
                            <?php if ($halaman_aktif < $total_halaman): ?>

                                <a href="?halaman=<?= $halaman_aktif + 1; ?>&status=<?= $status; ?>"
                                    class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-sm">

                                    Next

                                </a>

                            <?php else: ?>

                                <button
                                    class="px-4 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-400 text-sm cursor-not-allowed">

                                    Next

                                </button>

                            <?php endif; ?>

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
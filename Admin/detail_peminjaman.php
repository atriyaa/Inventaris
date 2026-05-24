<?php
require_once __DIR__ . "/../config/database.php";
session_start();

/*
|--------------------------------------------------------------------------
| VALIDASI ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {

    header("Location: peminjaman.php");
    exit;
}

$id_peminjaman = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| QUERY DATA PEMINJAMAN
|--------------------------------------------------------------------------
*/

$query_peminjaman = mysqli_query($conn, "
    SELECT 
        peminjaman.*,
        admin.username

    FROM peminjaman

    LEFT JOIN admin
        ON peminjaman.id_admin = admin.id

    WHERE peminjaman.id_peminjaman = '$id_peminjaman'
");

$data_peminjaman = mysqli_fetch_assoc($query_peminjaman);

/*
|--------------------------------------------------------------------------
| JIKA DATA TIDAK ADA
|--------------------------------------------------------------------------
*/

if (!$data_peminjaman) {

    $_SESSION['error'] = "Data peminjaman tidak ditemukan";

    header("Location: peminjaman.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| QUERY DETAIL BARANG YANG DIPINJAM
|--------------------------------------------------------------------------
|
| Alur relasi:
|
| peminjaman
|     ↓
| peminjaman_detail
|     ↓
| barang_detail
|     ↓
| barang
|     ↓
| kategori
|
|--------------------------------------------------------------------------
*/

$query_detail = mysqli_query($conn, "
    SELECT

        peminjaman_detail.id_pinjam_detail,
        peminjaman_detail.tgl_kembali,
        peminjaman_detail.kondisi_saat_kembali,

        barang_detail.id_detail,
        barang_detail.kode_unit,
        barang_detail.status,

        barang.id_barang,
        barang.nama_barang,

        kategori.nama_kategori

    FROM peminjaman_detail

    INNER JOIN barang_detail
        ON peminjaman_detail.id_detail = barang_detail.id_detail

    INNER JOIN barang
        ON barang_detail.id_barang = barang.id_barang

    LEFT JOIN kategori
        ON barang.id_kategori = kategori.id_kategori

    WHERE peminjaman_detail.id_peminjaman = '$id_peminjaman'

    ORDER BY barang.nama_barang ASC
");

/*
|--------------------------------------------------------------------------
| TOTAL BARANG
|--------------------------------------------------------------------------
*/

$total_barang = mysqli_num_rows($query_detail);

?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Peminjaman</title>

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

            <!-- BREADCRUMB -->
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">

                <a href="peminjaman.php"
                   class="hover:text-blue-600 transition">

                    Data Peminjaman

                </a>

                <i class="fa-solid fa-chevron-right text-xs"></i>

                <span class="text-gray-700 font-medium">
                    Detail Peminjaman
                </span>

            </div>

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <div>

                    <h1 class="text-3xl font-bold text-gray-800">
                        Detail Peminjaman
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Informasi lengkap transaksi peminjaman barang
                    </p>

                </div>

                <!-- BUTTON -->
                <div class="flex items-center gap-3">

                    <a href="peminjaman.php"
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 transition text-sm font-medium">

                        <i class="fa-solid fa-arrow-left"></i>

                        Kembali

                    </a>

                    <?php if($data_peminjaman['status_pinjam'] == 'Proses'): ?>

                        <a href="kembalikan.php?id=<?= $data_peminjaman['id_peminjaman']; ?>"
                           onclick="return confirm('Yakin ingin menyelesaikan peminjaman ini?')"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white transition text-sm font-medium">

                            <i class="fa-solid fa-rotate-left"></i>

                            Kembalikan

                        </a>

                    <?php endif; ?>

                </div>

            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                <!-- INFO PEMINJAMAN -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                    <!-- HEADER -->
                    <div class="px-6 py-5 border-b border-gray-200">

                        <h2 class="text-lg font-semibold text-gray-800">
                            Informasi Peminjaman
                        </h2>

                    </div>

                    <!-- CONTENT -->
                    <div class="p-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- NAMA -->
                            <div>

                                <p class="text-sm text-gray-500 mb-1">
                                    Nama Peminjam
                                </p>

                                <h3 class="font-semibold text-gray-800">
                                    <?= htmlspecialchars($data_peminjaman['nama_peminjam']); ?>
                                </h3>

                            </div>

                            <!-- ADMIN -->
                            <div>

                                <p class="text-sm text-gray-500 mb-1">
                                    Admin
                                </p>

                                <h3 class="font-semibold text-gray-800">
                                    <?= $data_peminjaman['username'] ?? '-'; ?>
                                </h3>

                            </div>

                            <!-- TANGGAL PINJAM -->
                            <div>

                                <p class="text-sm text-gray-500 mb-1">
                                    Tanggal Pinjam
                                </p>

                                <h3 class="font-semibold text-gray-800">
                                    <?= date('d F Y', strtotime($data_peminjaman['tanggal_pinjam'])); ?>
                                </h3>

                            </div>

                            <!-- TANGGAL KEMBALI -->
                            <div>

                                <p class="text-sm text-gray-500 mb-1">
                                    Tanggal Kembali
                                </p>

                                <h3 class="font-semibold text-gray-800">
                                    <?= date('d F Y', strtotime($data_peminjaman['tanggal_kembali'])); ?>
                                </h3>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- STATUS -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                    <!-- HEADER -->
                    <div class="px-6 py-5 border-b border-gray-200">

                        <h2 class="text-lg font-semibold text-gray-800">
                            Status Peminjaman
                        </h2>

                    </div>

                    <!-- CONTENT -->
                    <div class="p-6 flex flex-col items-center justify-center">

                        <?php if($data_peminjaman['status_pinjam'] == 'Proses'): ?>

                            <div class="w-20 h-20 rounded-full bg-yellow-100 flex items-center justify-center mb-4">

                                <i class="fa-solid fa-clock text-3xl text-yellow-600"></i>

                            </div>

                            <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">

                                Sedang Dipinjam

                            </span>

                        <?php else: ?>

                            <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-4">

                                <i class="fa-solid fa-check text-3xl text-green-600"></i>

                            </div>

                            <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">

                                Sudah Dikembalikan

                            </span>

                        <?php endif; ?>

                        <div class="mt-5 text-center">

                            <p class="text-sm text-gray-500">
                                Total Barang
                            </p>

                            <h3 class="text-3xl font-bold text-blue-600 mt-1">
                                <?= $total_barang; ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

            <!-- TABLE BARANG -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                <!-- HEADER -->
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Daftar Barang Dipinjam
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Detail seluruh barang yang dipinjam
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
                                    Kode Barang
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Nama Barang
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Kategori
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Status Barang
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Kondisi Kembali
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200">

                        <?php if(mysqli_num_rows($query_detail) > 0): ?>

                            <?php $no = 1; ?>

                            <?php while($detail = mysqli_fetch_assoc($query_detail)): ?>

                                <tr class="hover:bg-gray-50 transition">

                                    <!-- NO -->
                                    <td class="px-6 py-4">
                                        <?= $no++; ?>
                                    </td>

                                    <!-- KODE -->
                                    <td class="px-6 py-4">

                                        <span class="font-mono text-blue-600 font-semibold">

                                            <?= $detail['kode_unit']; ?>

                                        </span>

                                    </td>

                                    <!-- NAMA -->
                                    <td class="px-6 py-4 font-medium text-gray-800">

                                        <?= htmlspecialchars($detail['nama_barang']); ?>

                                    </td>

                                    <!-- KATEGORI -->
                                    <td class="px-6 py-4 text-gray-600">

                                        <?= $detail['nama_kategori']; ?>

                                    </td>

                                    <!-- STATUS -->
                                    <td class="px-6 py-4 text-center">

                                        <?php if($detail['status'] == 'Dipinjam'): ?>

                                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">

                                                Dipinjam

                                            </span>

                                        <?php else: ?>

                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">

                                                Tersedia

                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <!-- KONDISI -->
                                    <td class="px-6 py-4 text-center">

                                        <?php if($detail['kondisi_saat_kembali']): ?>

                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">

                                                <?= $detail['kondisi_saat_kembali']; ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="text-gray-400 italic text-sm">

                                                Belum dikembalikan

                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="6" class="px-6 py-12 text-center">

                                    <div class="flex flex-col items-center">

                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">

                                            <i class="fa-solid fa-box-open text-2xl text-gray-400"></i>

                                        </div>

                                        <h3 class="text-lg font-semibold text-gray-700 mb-1">
                                            Tidak ada barang
                                        </h3>

                                        <p class="text-sm text-gray-500">
                                            Belum ada detail barang dipinjam
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

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
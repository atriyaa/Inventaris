<?php
require_once __DIR__ . "/../config/database.php";
session_start();

/*
|--------------------------------------------------------------------------
| QUERY BARANG TERSEDIA
|--------------------------------------------------------------------------
*/

$query_barang = mysqli_query($conn, "
    SELECT

        barang_detail.id_detail,
        barang_detail.kode_unit,
        barang_detail.kondisi,
        barang_detail.status,
        barang_detail.lokasi_meja,
        barang_detail.lokasi_ruang,

        barang.id_barang,
        barang.nama_barang,
        barang.merk,
        barang.tipe,

        kategori.nama_kategori

    FROM barang_detail

    INNER JOIN barang
        ON barang_detail.id_barang = barang.id_barang

    LEFT JOIN kategori
        ON barang.id_kategori = kategori.id_kategori

    WHERE barang_detail.status = 'Tersedia'

    ORDER BY barang.nama_barang ASC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Peminjaman</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

            <!-- HEADER -->
            <div class="mb-6">

                <div class="flex items-center justify-between flex-wrap gap-4">

                    <div>

                        <h1 class="text-3xl font-bold text-gray-800">
                            Tambah Peminjaman
                        </h1>

                        <p class="text-gray-500 mt-1">
                            Tambahkan transaksi peminjaman barang laboratorium
                        </p>

                    </div>

                    <a href="peminjaman.php"
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 transition text-sm font-medium">

                        <i class="fa-solid fa-arrow-left"></i>

                        Kembali

                    </a>

                </div>

            </div>

            <!-- ALERT -->
            <?php if(isset($_SESSION['error'])): ?>

                <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-xl">

                    <?= $_SESSION['error']; ?>

                </div>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>

            <!-- FORM -->
            <form action="proses_tambah_peminjaman.php" method="POST">

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    <!-- LEFT -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-fit">

                        <!-- HEADER -->
                        <div class="px-6 py-5 border-b border-gray-200">

                            <h2 class="text-lg font-semibold text-gray-800">
                                Informasi Peminjaman
                            </h2>

                        </div>

                        <!-- BODY -->
                        <div class="p-6 space-y-5">

                            <!-- NAMA -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">

                                    Nama Peminjam

                                </label>

                                <input
                                    type="text"
                                    name="nama_peminjam"
                                    required
                                    placeholder="Masukkan nama peminjam"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >

                            </div>

                            <!-- TGL PINJAM -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">

                                    Tanggal Pinjam

                                </label>

                                <input
                                    type="date"
                                    name="tanggal_pinjam"
                                    required
                                    value="<?= date('Y-m-d'); ?>"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >

                            </div>

                            <!-- TGL KEMBALI -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">

                                    Tanggal Kembali

                                </label>

                                <input
                                    type="date"
                                    name="tanggal_kembali"
                                    required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >

                            </div>

                            <!-- INFO -->
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">

                                <div class="flex items-start gap-3">

                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">

                                        <i class="fa-solid fa-circle-info text-blue-600"></i>

                                    </div>

                                    <div>

                                        <h3 class="font-semibold text-blue-700 mb-1">
                                            Informasi
                                        </h3>

                                        <p class="text-sm text-blue-600 leading-relaxed">
                                            Pilih barang yang ingin dipinjam.
                                            Barang yang dipilih otomatis akan berubah status menjadi
                                            <b>Tidak Tersedia</b>.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                        <!-- HEADER -->
                        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-4">

                            <div>

                                <h2 class="text-lg font-semibold text-gray-800">
                                    Daftar Barang Tersedia
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    Pilih barang yang ingin dipinjam
                                </p>

                            </div>

                            <!-- SEARCH -->
                            <div class="relative">

                                <input
                                    type="text"
                                    id="searchInput"
                                    placeholder="Cari barang..."
                                    class="border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >

                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>

                            </div>

                        </div>

                        <!-- TABLE -->
                        <div class="overflow-x-auto">

                            <table class="w-full text-sm" id="barangTable">

                                <thead class="bg-gray-50 border-b border-gray-200 text-gray-600">

                                    <tr>

                                        <th class="px-6 py-4 text-center">
                                            Pilih
                                        </th>

                                        <th class="px-6 py-4 text-left font-semibold">
                                            Kode Unit
                                        </th>

                                        <th class="px-6 py-4 text-left font-semibold">
                                            Nama Barang
                                        </th>

                                        <th class="px-6 py-4 text-left font-semibold">
                                            Merk / Tipe
                                        </th>

                                        <th class="px-6 py-4 text-center font-semibold">
                                            Kondisi
                                        </th>

                                        <th class="px-6 py-4 text-center font-semibold">
                                            Lokasi
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-gray-200">

                                <?php if(mysqli_num_rows($query_barang) > 0): ?>

                                    <?php while($barang = mysqli_fetch_assoc($query_barang)): ?>

                                        <tr class="hover:bg-gray-50 transition barang-row">

                                            <!-- CHECKBOX -->
                                            <td class="px-6 py-4 text-center">

                                                <input
                                                    type="checkbox"
                                                    name="barang[]"
                                                    value="<?= $barang['id_detail']; ?>"
                                                    class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500"
                                                >

                                            </td>

                                            <!-- KODE -->
                                            <td class="px-6 py-4">

                                                <span class="font-mono text-blue-600 font-semibold">

                                                    <?= $barang['kode_unit']; ?>

                                                </span>

                                            </td>

                                            <!-- NAMA -->
                                            <td class="px-6 py-4">

                                                <div class="font-semibold text-gray-800">

                                                    <?= htmlspecialchars($barang['nama_barang']); ?>

                                                </div>

                                                <div class="text-xs text-gray-500 mt-1">

                                                    <?= $barang['nama_kategori']; ?>

                                                </div>

                                            </td>

                                            <!-- MERK -->
                                            <td class="px-6 py-4 text-gray-600">

                                                <div>
                                                    <?= $barang['merk']; ?>
                                                </div>

                                                <div class="text-xs text-gray-400 mt-1">
                                                    <?= $barang['tipe']; ?>
                                                </div>

                                            </td>

                                            <!-- KONDISI -->
                                            <td class="px-6 py-4 text-center">

                                                <?php if($barang['kondisi'] == 'Baik'): ?>

                                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">

                                                        Baik

                                                    </span>

                                                <?php elseif($barang['kondisi'] == 'Rusak'): ?>

                                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">

                                                        Rusak

                                                    </span>

                                                <?php else: ?>

                                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">

                                                        Perbaikan

                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <!-- LOKASI -->
                                            <td class="px-6 py-4 text-center text-gray-600">

                                                <div>
                                                    <?= $barang['lokasi_ruang']; ?>
                                                </div>

                                                <div class="text-xs text-gray-400 mt-1">
                                                    <?= $barang['lokasi_meja']; ?>
                                                </div>

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
                                                    Tidak ada barang tersedia
                                                </h3>

                                                <p class="text-sm text-gray-500">
                                                    Semua barang sedang dipinjam
                                                </p>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                        <!-- FOOTER -->
                        <div class="px-6 py-5 border-t border-gray-200 flex items-center justify-between flex-wrap gap-4">

                            <div class="text-sm text-gray-500">

                                Pilih satu atau lebih barang untuk dipinjam

                            </div>

                            <div class="flex items-center gap-3">

                                <a href="peminjaman.php"
                                   class="px-5 py-3 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 transition text-sm font-medium">

                                    Batal

                                </a>

                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition text-sm font-medium"
                                >

                                    <i class="fa-solid fa-floppy-disk"></i>

                                    Simpan Peminjaman

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </main>

    </div>

</div>

<!-- SEARCH SCRIPT -->
<script>

const searchInput = document.getElementById('searchInput');

searchInput.addEventListener('keyup', function() {

    let filter = this.value.toLowerCase();

    let rows = document.querySelectorAll('.barang-row');

    rows.forEach(function(row) {

        let text = row.innerText.toLowerCase();

        if(text.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }

    });

});

</script>

</body>
</html>
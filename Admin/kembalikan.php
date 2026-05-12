<?php

require_once __DIR__ . "/../config/database.php";

session_start();

/*
|--------------------------------------------------------------------------
| VALIDASI ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {

    $_SESSION['error'] = "ID peminjaman tidak ditemukan";

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
    SELECT *
    FROM peminjaman
    WHERE id_peminjaman = '$id_peminjaman'
");

$data_peminjaman = mysqli_fetch_assoc($query_peminjaman);

/*
|--------------------------------------------------------------------------
| VALIDASI DATA
|--------------------------------------------------------------------------
*/

if (!$data_peminjaman) {

    $_SESSION['error'] = "Data peminjaman tidak ditemukan";

    header("Location: peminjaman.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| VALIDASI STATUS
|--------------------------------------------------------------------------
|
| Jika sudah selesai tidak boleh dikembalikan lagi
|
|--------------------------------------------------------------------------
*/

if ($data_peminjaman['status_pinjam'] == 'Selesai') {

    $_SESSION['error'] = "Peminjaman sudah selesai";

    header("Location: detail_peminjaman.php?id=$id_peminjaman");
    exit;
}

/*
|--------------------------------------------------------------------------
| QUERY DETAIL BARANG
|--------------------------------------------------------------------------
*/

$query_detail = mysqli_query($conn, "
    SELECT

        peminjaman_detail.id_pinjam_detail,
        peminjaman_detail.id_detail,

        barang_detail.kode_unit,

        barang.nama_barang

    FROM peminjaman_detail

    INNER JOIN barang_detail
        ON peminjaman_detail.id_detail = barang_detail.id_detail

    INNER JOIN barang
        ON barang_detail.id_barang = barang.id_barang

    WHERE peminjaman_detail.id_peminjaman = '$id_peminjaman'
");

?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pengembalian Barang</title>

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
            <div class="flex items-center justify-between flex-wrap gap-4 mb-6">

                <div>

                    <h1 class="text-3xl font-bold text-gray-800">
                        Pengembalian Barang
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Proses pengembalian barang laboratorium
                    </p>

                </div>

                <a href="detail_peminjaman.php?id=<?= $id_peminjaman; ?>"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 transition text-sm font-medium">

                    <i class="fa-solid fa-arrow-left"></i>

                    Kembali

                </a>

            </div>

            <!-- INFO -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Informasi Peminjaman
                    </h2>

                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- PEMINJAM -->
                    <div>

                        <p class="text-sm text-gray-500 mb-1">
                            Nama Peminjam
                        </p>

                        <h3 class="font-semibold text-gray-800">
                            <?= htmlspecialchars($data_peminjaman['nama_peminjam']); ?>
                        </h3>

                    </div>

                    <!-- TGL PINJAM -->
                    <div>

                        <p class="text-sm text-gray-500 mb-1">
                            Tanggal Pinjam
                        </p>

                        <h3 class="font-semibold text-gray-800">
                            <?= date('d F Y', strtotime($data_peminjaman['tanggal_pinjam'])); ?>
                        </h3>

                    </div>

                    <!-- TGL KEMBALI -->
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

            <!-- FORM -->
            <form action="proses_kembalikan.php" method="POST">

                <input
                    type="hidden"
                    name="id_peminjaman"
                    value="<?= $id_peminjaman; ?>"
                >

                <!-- CARD -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                    <!-- HEADER -->
                    <div class="px-6 py-5 border-b border-gray-200">

                        <h2 class="text-lg font-semibold text-gray-800">
                            Kondisi Barang Saat Dikembalikan
                        </h2>

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
                                        Kode Unit
                                    </th>

                                    <th class="px-6 py-4 text-left font-semibold">
                                        Nama Barang
                                    </th>

                                    <th class="px-6 py-4 text-center font-semibold">
                                        Kondisi Saat Kembali
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200">

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

                                    <!-- KONDISI -->
                                    <td class="px-6 py-4">

                                        <input
                                            type="hidden"
                                            name="id_detail[]"
                                            value="<?= $detail['id_detail']; ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="id_pinjam_detail[]"
                                            value="<?= $detail['id_pinjam_detail']; ?>"
                                        >

                                        <select
                                            name="kondisi[]"
                                            required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >

                                            <option value="">
                                                -- Pilih Kondisi --
                                            </option>

                                            <option value="Baik">
                                                Baik
                                            </option>

                                            <option value="Rusak">
                                                Rusak
                                            </option>

                                            <option value="Perbaikan">
                                                Perbaikan
                                            </option>

                                        </select>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                    <!-- FOOTER -->
                    <div class="px-6 py-5 border-t border-gray-200 flex items-center justify-end gap-3">

                        <a href="detail_peminjaman.php?id=<?= $id_peminjaman; ?>"
                           class="px-5 py-3 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 transition text-sm font-medium">

                            Batal

                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white transition text-sm font-medium"
                        >

                            <i class="fa-solid fa-check"></i>

                            Selesaikan Pengembalian

                        </button>

                    </div>

                </div>

            </form>

        </main>

    </div>

</div>

</body>
</html>
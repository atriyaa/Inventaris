<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();

    // 1. Query Data Pilihan (Dropdown)
    $sql_nama_barang = mysqli_query($conn, "SELECT id_barang, nama_barang FROM barang GROUP BY id_barang");

    if (!$sql_nama_barang) {
        die("Query barang error: " . mysqli_error($conn));
    }

    $sql_semua_detail = mysqli_query($conn, "SELECT id_detail, id_barang, kode_unit FROM barang_detail");

    $array_detail = [];
    while ($row = mysqli_fetch_assoc($sql_semua_detail)) {
        $array_detail[$row['id_barang']][] = [
            'id'   => $row['id_detail'],
            'kode' => $row['kode_unit']
        ];
    }

    // 2. Proses Hanya Jalankan Saat Form Di-submit
    if (isset($_POST["tambah_license"])) {
        $id_detail        = mysqli_real_escape_string($conn, $_POST['id_detail']);
        $nama_software    = mysqli_real_escape_string($conn, $_POST['nama_software']);
        $kode_lisensi     = mysqli_real_escape_string($conn, $_POST['kode_lisensi']);
        $tanggal_aktivasi = mysqli_real_escape_string($conn, $_POST['tanggal_aktivasi']);
        $tgl_kadaluarsa   = mysqli_real_escape_string($conn, $_POST['tgl_kadaluarsa']);

        // Query Insert
        $query_insert = "INSERT INTO software_license (id_detail, nama_software, kode_lisensi, tanggal_aktivasi, tgl_kadaluarsa) 
                         VALUES ('$id_detail', '$nama_software', '$kode_lisensi', '$tanggal_aktivasi', '$tgl_kadaluarsa')";

        // Eksekusi Query ke Database
        if (mysqli_query($conn, $query_insert)) {
            echo "<script>
            alert('Data lisensi berhasil disimpan!');
            window.location.href='perawatan.php';
            </script>";
            exit();
        } else {
            echo "<script>
            alert('Gagal menyimpan data: " . mysqli_error($conn) . "');
            </script>";
        }
    }
    // HAPUS BLOK 'ELSE' LAMA DI SINI
?>r
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style_form.css">
    <title>InventarisApp Tambah Barang</title>
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
        <div class="flex-1 flex-col overflow-y-auto">
            <?php  include '../include/header_hlm.php'; ?>
            <div class="p-6">
                <div class="mb-4">
                    <h1 class="text-2xl font-semibold text gray-800">Tambah Software</h1>
                </div>
                <?php if (isset($_GET['tambah_data']) && $_GET['tambah_data'] == 'success'): ?>
                <div class="bg-green-500 text-white p-3 rounded mb-4 text-center shadow-sm" id="alert">
                    <i class="fas fa-check-circle mr-2"></i> Barang Berhasil Di Tambah!
                </div>
                <?php endif; ?>
                <div class="bg-white rounded shadow-md border-t-4 border[#3c8dbc]">
                <div class="p-6">
                    <div class="mb-4 flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-800">Tambah License Software</h1>
                            <p class="text-sm text-gray-500">Masukkan detail license software baru ke dalam sistem.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded shadow-md border-t-4 border-[#3c8dbc]">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                            <i class="fas fa-box-open text-[#3c8dbc] mr-2"></i>
                            <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">Form Detail license</h3>
                        </div>

                        <form method="POST" autocomplete="off" class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                            
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Software</label>
                                    <input type="text" name="nama_software" placeholder="Contoh: Microsoft Office" required
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">License Key</label>
                                    <input type="text" name="license_key" placeholder="Contoh: XXXXX-XXXXX-XXXXX-XXXXX" required
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipe</label>
                                    <select name="tipe_license" required
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                        <option value="Subrection">Subrection</option>
                                        <option value="Lifetime">Lifetime</option>
                                        <option value="OEM">OEM</option>
                                    </select>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Pembelian</label>
                                    <input type="date" name="tanggal_pembelian" required
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Expired</label>
                                    <input type="date" name="tanggal_expired" required
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah Pengguna</label>
                                    <input type="number" name="jumlah_user" min="1" required placeholder="0"
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                                        <select name="status_aktif" required
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                        <option value=1>Aktif</option>
                                        <option value=0>Non-Aktif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6 space-y-4">

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan Tambahan</label>
                                    <textarea name="keterangan" rows="2" placeholder="Catatan lainnya..."
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20"></textarea>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                                <a href="license.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                </a>
                                <button type="submit" name="tambah_license"  class="px-4 py-2 bg-[#3c8dbc] hover:bg-[#367fa9] text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
                                    <i class="fas fa-save mr-2"></i> Simpan Data Barang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

</body>
</html>
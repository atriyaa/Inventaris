<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $sql_nama_barang = mysqli_query($conn, "SELECT id_barang, nama_barang FROM barang GROUP BY id_barang");

    // 2. Query mengambil relasi id_barang, id_detail, dan kode_unit
    $sql_semua_detail = mysqli_query($conn, "SELECT id_detail, id_barang, kode_unit FROM barang_detail");

    $array_detail = [];
    while ($row = mysqli_fetch_assoc($sql_semua_detail)) {
        // Menyusun array bertingkat berdasarkan id_barang
        $array_detail[$row['id_barang']][] = [
            'id'   => $row['id_detail'],
            'kode' => $row['kode_unit']
        ];
    }
    if (!$sql_nama_barang) {
        die("Query kategori error: " . mysqli_error($conn));
    }
    $create_message = "";
    $message_type = "";

    if (isset($_POST['tambah_data'])) {
        // 1. Ambil data dari form input dan amankan dari SQL Injection
        $id_detail            = mysqli_real_escape_string($conn, $_POST['id_detail']);
        $tgl_perawatan        = mysqli_real_escape_string($conn, $_POST['tgl_perawatan']);
        $biaya                = mysqli_real_escape_string($conn, $_POST['biaya']);
        $petugas              = mysqli_real_escape_string($conn, $_POST['petugas']);
        $keterangan_perawatan = mysqli_real_escape_string($conn, $_POST['keterangan_perawatan']);

        // 2. Mulai Database Transaction (Opsional, agar jika salah satu query gagal, data dibatalkan semua)
        mysqli_begin_transaction($conn);

        try {
            // QUERY A: Insert data ke tabel perawatan
            $query_insert = "INSERT INTO perawatan (id_detail, tgl_perawatan, biaya, keterangan_perawatan, petugas) 
                            VALUES ('$id_detail', '$tgl_perawatan', '$biaya', '$keterangan_perawatan', '$petugas')";
            mysqli_query($conn, $query_insert);

            // QUERY B: Mengubah status kondisi barang di tabel detail barang (tabel 'barang') menjadi 'Perbaikan'
            // Sesuaikan nama tabel 'barang' dan kolom 'kondisi' jika di database Anda berbeda nama
            $query_update_status = "UPDATE barang_detail SET kondisi = 'Perbaikan' WHERE id_detail = '$id_detail'";
            mysqli_query($conn, $query_update_status);

            // Jika kedua query berhasil, simpan permanen ke database
            mysqli_commit($conn);

            echo "<script>
                    alert('Data perawatan berhasil disimpan dan status unit berhasil diubah menjadi Perbaikan!');
                    window.location.href='perawatan.php';
                </script>";
        } catch (Exception $e) {
            // Jika ada query yang error, batalkan semua perubahan data
            mysqli_rollback($conn);
            echo "<script>
                    alert('Gagal menyimpan data: " . mysqli_error($conn) . "');
                </script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <link rel="stylesheet" href="style._form.css">
    <title>InventarisApp - Form Kerusakan Barang</title>
    <style>
        .ts-wrapper.focus .ts-control {
        border-color: #0d9488 !important; /* Teal-600 */
        box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.2) !important;
        }
        .ts-dropdown .active {
            background-color: #0d9488 !important; /* Warna hover menu jadi teal */
            color: white !important;
        }
        aside { transition: width 0.3s ease; }
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
            <?php include '../include/header_hlm.php'  ?>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Selamat Datang, <span class="font-semibold"><?php echo $_SESSION["username"]; ?></span></p>
                    <h1 class="text-2xl font-semibold text-gray-800">Tambah Data Perawatan</h1>
                    <nav class="text-sm text-gray-500 mt-1">
                        <a href="dashboard.php" class="hover:text-[#3c8dbc]">Dashboard</a> 
                        <span class="mx-1">></span> 
                        <a href="perawatan.php" class="hover:text-[#3c8dbc]">Data Perawatan</a>
                        <span class="mx-1">></span> 
                        <span class="text-gray-400">Tambah Data</span>
                    </nav>
                </div>
                <?php if (isset($_GET['perawatan']) && $_GET['perawatan'] == 'success'): ?>
                    <div class="bg-green-500 text-white p-3 rounded mb-4 text-center shadow-sm" id="alert">
                        <i class="fas fa-check-circle mr-2"></i> Laporan Berhasil!
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded shadow-md border-t-4 border-teal-500"> 
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                        <i class="fas fa-tools text-teal-500 mr-2"></i>
                        <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">Form Maintenance / Perawatan</h3>
                    </div>

                    <form action="" method="POST" autocomplete="off" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Barang:</label>
                                <select name="id_barang" id="pilih_barang" placeholder="Cari atau pilih barang..." autocomplete="off" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <?php while ($k = mysqli_fetch_assoc($sql_nama_barang)): ?>
                                        <option value="<?= $k['id_barang']; ?>"><?= $k['nama_barang']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-600 mb-1">Detail Barang (Kode Unit):</label>
                                <select name="id_detail" id="pilih_detail" placeholder="Pilih nama barang terlebih dahulu..." autocomplete="off" required>
                                    <option value="">-- Pilih Nama Barang Terlebih Dahulu --</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Perawatan</label>
                                <input type="date" name="tgl_perawatan" required value="<?= date('Y-m-d'); ?>"
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Biaya Perawatan (Rp)</label>
                                <input type="number" name="biaya" min="0" placeholder="0" required
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Admin</label>
                                <input type="text" name="petugas" value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : 'anis'; ?>" required
                                    class="w-full border-gray-300 rounded shadow-sm bg-gray-50 focus:border-teal-500 focus:ring focus:ring-teal-200">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi / Keterangan Perawatan</label>
                                <textarea name="keterangan_perawatan" rows="4" placeholder="Contoh: Pembersihan debu, penggantian pasta processor, atau pengecekan software..." required
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="perawatan.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="submit" name="tambah_data" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
                                <i class="fas fa-save mr-2"></i> Simpan Data Perawatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <script>
            // Membaca data array dari PHP
            const dataDetailBarang = <?php echo json_encode($array_detail); ?>;

            // Inisialisasi Dropdown 1 (Nama Barang)
            var tsBarang = new TomSelect("#pilih_barang", {
                create: false,
                sortField: { field: "text", direction: "asc" }
            });

            // Inisialisasi Dropdown 2 (Detail Barang)
            var tsDetail = new TomSelect("#pilih_detail", {
                create: false,
                placeholder: "-- Pilih Nama Barang Terlebih Dahulu --"
            });

            // Kunci dropdown detail di awal halaman load
            tsDetail.disable();

            // Logika saat Dropdown Nama Barang dipilih
            tsBarang.on('change', function(idBarang) {
                // 1. Bersihkan isi opsi lama di Tom Select Detail
                tsDetail.clear();
                tsDetail.clearOptions();

                // 2. Jika user memilih opsi kosong kembali atau data tidak ditemukan
                if (idBarang === "" || !dataDetailBarang[idBarang]) {
                    tsDetail.disable();
                    // Cara ganti placeholder Tom Select yang benar:
                    tsDetail.settings.placeholder = "-- Pilih Nama Barang Terlebih Dahulu --";
                    tsDetail.control_input.placeholder = "-- Pilih Nama Barang Terlebih Dahulu --";
                    return;
                }

                // 3. Jika data ada, buka kuncinya
                tsDetail.enable();
                tsDetail.settings.placeholder = "Cari atau pilih kode unit...";
                tsDetail.control_input.placeholder = "Cari atau pilih kode unit...";
                
                // 4. Masukkan opsi kode_unit baru ke Tom Select Detail
                dataDetailBarang[idBarang].forEach(function(item) {
                    tsDetail.addOption({
                        value: item.id,   // id_detail disimpan ke database
                        text: item.kode   // kode_unit muncul di layar
                    });
                });
                
                // Refresh pilihan agar langsung muncul di layar
                tsDetail.refreshOptions(false);
            });
        </script>
</body>    
</html>

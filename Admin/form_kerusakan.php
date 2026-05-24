<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $sql_nama_barang = mysqli_query($conn, "SELECT id_barang, nama_barang FROM barang GROUP BY id_barang");

    $sql_semua_detail = mysqli_query($conn, "SELECT id_detail, id_barang, kode_unit FROM barang_detail");

    $array_detaik = [];
    while ($row = mysqli_fetch_assoc($sql_semua_detail)){
        $array_detail[$row['id_barang']][] = [
            'id' => $row['id_detail'],
            'kode' => $row['kode_unit']
        ];
    }
    if (!$sql_nama_barang) {
        die("Query kategori error: " . mysqli_error($conn));
    }
    $create_message = "";
    $message_type = "";

    if (isset($_POST["tambah_kerusakan"])) {
        $id_detail              = mysqli_real_escape_string($conn, $_POST['id_detail']);
        $tanggal_lapor          = mysqli_real_escape_string($conn, $_POST['tanggal_lapor']);
        $deskripsi_kerusakan    = mysqli_real_escape_string($conn, $_POST['deskripsi_kerusakan']);
        $status_perbaikan       = mysqli_real_escape_string($conn, $_POST['status_perbaikan']);

        mysqli_begin_transaction($conn);
        try {
            $query_insert = "INSERT INTO kerusakan (id_detail, tanggal_lapor, deskripsi_kerusakan, status_perbaikan) VALUES ('$id_detail', '$tanggal_lapor', '$deskripsi_kerusakan', '$status_perbaikan')";
        mysqli_query($conn, $query_insert);

        $query_update_status = "UPDATE barang_detail SET kondisi = 'Perbaikan' WHERE id_detail = '$id_detail'";
        mysqli_query($conn, $query_update_status);

        mysqli_commit($conn);
        echo "<script>
            alert('Data perawatan berhasil disimpan dan status unit berhasil diubah menjadi Perbaikan!');
            window.location.href='perawatan.php';
        </script>";
        } catch (Exception $e) {
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
            <?php include '../include/header_hlm.php'; ?>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Selamat Datang, <span class="font-semibold"><?php echo $_SESSION["username"]; ?></span></p>
                    <h1 class="text-2xl font-semibold text-gray-800">Tambah Data kerusakan</h1>
                    <nav class="text-sm text-gray-500 mt-1">
                        <a href="dashboard.php" class="hover:text-[#3c8dbc]">Dashboard</a> 
                        <span class="mx-1">></span> 
                        <a href="kerusakan.php" class="hover:text-[#3c8dbc]">Data Perawatan</a>
                        <span class="mx-1">></span> 
                        <span class="text-gray-400">Tambah Data</span>
                    </nav>
                </div>
                <?php if (isset($_GET['perawatan']) && $_GET['perawatan'] == 'success'): ?>
                    <div class="bg-green-500 text-white p-3 rounded mb-4 text-center shadow-sm" id="alert">
                        <i class="fas fa-check-circle mr-2"></i> Laporan Berhasil!
                    </div>
                <?php endif; ?>
                
                <div class="bg-white rounded shadow-md border-t-4 border-red-500"> 
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">Form Input Kerusakan</h3>
                    </div>
            
                    <form  method="POST" autocomplete="off" class="p-6">
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
                                <label class="block text-sm font-bold text-gray-700 mb-1">Status Perbaikan</label>
                                <select name="status_perbaikan" required 
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Proses Perbaikan">Proses Perbaikan</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Afkir">Afkir</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Lapor</label>
                                <input type="date" name="tanggal_lapor" required value="<?= date('Y-m-d'); ?>"
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Kerusakan</label>
                                <textarea name="deskripsi_kerusakan" rows="4" placeholder="Jelaskan detail kerusakan..." required 
                                class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"></textarea>
                            </div>

                        </div>
            
                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="kerusakan.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="submit" name="tambah_kerusakan" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
                                <i class="fas fa-save mr-2"></i> Simpan Laporan
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
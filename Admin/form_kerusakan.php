<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $query_barang = mysqli_query($conn, "SELECT * FROM barang");

    if (!$query_barang) {
        die("Query kategori error: " . mysqli_error($conn));
    }
    $create_message = "";
    $message_type = "";

    if (isset($_POST["tambah_kerusakan"])) {
    $id_barang = $_POST["id_barang"];
    $tanggal_lapor = $_POST["tanggal_lapor"];
    $deskripsi_kerusakan = $_POST["deskripsi_kerusakan"];
    $tingkat_kerusakan = $_POST["tingkat_kerusakan"];
    $status_perbaikan = $_POST["status_perbaikan"];
    $biaya_perbaikan= $_POST["biaya_perbaikan"];

    $sql = "INSERT INTO kerusakan(id_barang, tanggal_lapor, deskripsi_kerusakan, tingkat_kerusakan, status_perbaikan, biaya_perbaikan) VALUES ('$id_barang', '$tanggal_lapor', '$deskripsi_kerusakan', '$tingkat_kerusakan', '$status_perbaikan', '$biaya_perbaikan')";
    if (mysqli_query($conn, $sql)) {
        header("Location: form_kerusakan.php?tambah_kerusakan=success");
        exit;
    } else {
        $create_message = "Data gagal ditambahkan";
        $message_type = "error";
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
    <link rel="stylesheet" href="style_form.css">
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
                <div id="infoBarangBox" class="hidden mb-6 bg-red-50 border border-red-200 rounded p-4">
                    <h5 class="text-red-700 font-bold mb-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i> Detail Stok Saat Ini
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <p><b>Nama Barang:</b> <span id="namaBarang">-</span></p>
                        <p><b>Total Stok di Sistem:</b> <span id="stokBarang">-</span> Unit</p>
                    </div>
                </div>
                <div class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-800">Laporan Kerusakan Barang</h1>
                    <p class="text-sm text-gray-500">Catat detail kerusakan barang untuk tindak lanjut perbaikan.</p>
                </div>
                <?php if (isset($_GET['tambah_kerusakan']) && $_GET['tambah_kerusakan'] == 'success'): ?>
                    <div class="bg-green-500 text-white p-3 rounded mb-4 text-center shadow-sm" id="alert">
                        <i class="fas fa-check-circle mr-2"></i> Laporan Berhasil!
                    </div>
                <?php endif; ?>
                
                <div class="bg-white rounded shadow-md border-t-4 border-red-500"> <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">Form Input Kerusakan</h3>
                    </div>
            
                    <form  method="POST" autocomplete="off" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Barang</label>
                                    <select id="barangSelect" name="id_barang" required 
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 transition-all">
                                        <option value="">-- Pilih Barang --</option>
                                        <?php 
                                        while($row = mysqli_fetch_assoc($query_barang)) { 
                                        ?>
                                        <option value="<?php echo $row['id']; ?>"
                                                data-nama="<?php echo $row['nama_barang']; ?>"
                                                data-stok="<?php echo $row['jumlah']; ?>">
                                            <?php echo $row['nama_barang']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Ditemukan</label>
                                    <input type="date" name="tanggal_lapor" required value="<?= date('Y-m-d'); ?>"
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Kerusakan</label>
                                    <textarea name="deskripsi_kerusakan" rows="4" placeholder="Jelaskan detail kerusakan..." required 
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"></textarea>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tingkat Kerusakan</label>
                                    <select name="tingkat_kerusakan" required 
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                                        <option value="Ringan">Rusak Ringan (Masih bisa dipakai)</option>
                                        <option value="Sedang">Rusak Sedang (Butuh servis)</option>
                                        <option value="Rusak Berat">Rusak Berat (Mati total/Ganti baru)</option>
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
                            </div>
                        </div>
            
                        <div class="mt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Biaya Perbaikan</label>
                            <input type="text" name="biaya_perbaikan" placeholder="Contoh: 120000"
                                class="w-full border-gray-300 rounded shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
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
document.getElementById('barangSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const infoBox = document.getElementById('infoBarangBox');
    
    if (this.value !== "") {
        document.getElementById('namaBarang').textContent = selectedOption.getAttribute('data-nama');
        document.getElementById('stokBarang').textContent = selectedOption.getAttribute('data-stok');
        infoBox.classList.remove('hidden');
    } else {
        infoBox.classList.add('hidden');
    }
    setTimeout(() => {
        const alert = document.getElementById('alert');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500); // hilang total
        }
    }, 5000); // 3000ms = 3 detik
});
</script>
</body>
</html>
<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $sql_nama_barang = mysqli_query($conn, "SELECT id,nama_barang FROM barang");
    if (!$sql_nama_barang) {
        die("Query kategori error: " . mysqli_error($conn));
    }
    $create_message = "";
    $message_type = "";

    if (isset($_POST["tambah_data"])) {
    $nama_barang = $_POST["nama_barang"];
    $tanggal_perbaikan = $_POST["tanggal_perbaikan"];
    $deskripsi = $_POST["deskripsi"];

    $sql = "INSERT INTO perawatan(tanggal_perbaikan, deskripsi, id) VALUES ('$tanggal_perbaikan', '$deskripsi', '$nama_barang')";
    if (mysqli_query($conn, $sql)) {
        header("Location: form_perawatan.php?perawatan=success");
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

                <div class="bg-white rounded shadow-md border-t-4 border-teal-500"> <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                        <i class="fas fa-tools text-teal-500 mr-2"></i>
                        <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">Form Maintenance / Perawatan</h3>
                    </div>

                    <form action="" method="POST" autocomplete="off" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang</label>
                                <select name="nama_barang" required
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 transition-all">
                                    <option value="">-- Pilih Barang --</option>
                                    <?php while ($k = mysqli_fetch_assoc($sql_nama_barang)): ?>
                                        <option value="<?= $k['id']; ?>"><?= $k['nama_barang']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Perawatan</label>
                                <input type="date" name="tanggal_perbaikan" required value="<?= date('Y-m-d'); ?>"
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Perawatan</label>
                                <textarea name="deskripsi" rows="4" placeholder="Contoh: Pembersihan debu, penggantian pasta processor, atau pengecekan software..."
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

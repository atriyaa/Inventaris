<?php
    require_once __DIR__ . "/../config/database.php";
session_start();
$create_message = '';

if (isset($_POST["pinjam"])) {
    $nama_peminjam = $_POST['nama_peminjam'];
    $keperluan = $_POST['keperluan'];
    $jumlah = $_POST['jml_brng_pinjam'];
    $barang_id = $_POST['barang_id'];

    $sql =  "INSERT INTO peminjaman (nama_peminjam, keperluan, jml_brng_pinjam, barang_id) VALUES ('$nama_peminjam', '$keperluan', '$jumlah', '$barang_id')";
    if (mysqli_query($conn, $sql)) {
        header("Location: pinjam.php?pinjam=success");
        exit;
    } else {
        $create_message = "Data gagal ditambahkan";
        $message_type = "error";
    }
}
$query = mysqli_query($conn, "SELECT barang.*, IFNULL(SUM(peminjaman.jml_brng_pinjam),0) AS sedang_dipinjam FROM barang
LEFT JOIN peminjaman ON barang.id = peminjaman.barang_id AND peminjaman.status='dipinjam' GROUP BY barang.id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style_form.css">
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
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <?php include '../include/menu.php'; ?>
        <div class="flex-1 flex-col overflow-y-auto">
            <?php include '../include/header_hlm.php'; ?>
            <div class="p-6">
                <div class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-800">Peminjaman Barang</h1>
                </div>

                <?php if (isset($_GET['pinjam']) && $_GET['pinjam'] == 'success'): ?>
                <div class="bg-green-500 text-white p-3 rounded mb-4 text-center shadow-sm" id="alert">
                    <i class="fas fa-check-circle mr-2"></i> Peminjaman berhasil!
                </div>
                <?php endif; ?>

                <div class="bg-white rounded shadow-md border-t-4 border-[#3c8dbc]">
                    <div class="p-6">
                        <div id="infoBarangBox" class="hidden mb-6 bg-blue-50 border border-blue-200 rounded p-4">
                            <h5 class="text-[#3c8dbc] font-bold mb-2 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Info Barang
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <p class="text-gray-700"><b>Nama Barang :</b> <span id="namaBarang" class="text-blue-700">-</span></p>
                                <p class="text-gray-700"><b>Stok Tersedia :</b> <span id="stokBarang" class="text-blue-700">-</span></p>
                                <p class="text-gray-700"><b>Sedang Dipinjam :</b> <span id="dipinjamBarang" class="text-blue-700">-</span></p>
                            </div>
                        </div>
                        <form autocomplete="off" method="POST" action="">
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Barang</label>
                                    <select id="barangSelect" name="barang_id" required 
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20 transition-all">
                                        <option value="">-- Pilih Barang --</option>
                                        <?php 
                                        // Pastikan variabel $query sudah dijalankan di atas
                                        while($row = mysqli_fetch_assoc($query)) { 
                                            $stok_tersedia = $row['jumlah'] - $row['sedang_dipinjam']; 
                                        ?>
                                        <option value="<?php echo $row['id']; ?>"
                                                data-nama="<?php echo $row['nama_barang']; ?>"
                                                data-stok="<?php echo $stok_tersedia; ?>"
                                                data-dipinjam="<?php echo $row['sedang_dipinjam']; ?>">
                                            <?php echo $row['nama_barang']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah</label>
                                    <input type="number" name="jml_brng_pinjam" required placeholder="0"
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Peminjam</label>
                                    <input type="text" name="nama_peminjam" required placeholder="Masukkan nama lengkap..."
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Keperluan</label>
                                    <textarea name="keperluan" required rows="3" placeholder="Alasan peminjaman..."
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20"></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                                    <a href="peminjaman.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
                                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                                    </a>
                                    <button name="pinjam" type="submit" class="px-4 py-2 bg-[#3c8dbc] hover:bg-[#367fa9] text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
                                        <i class="fas fa-save mr-2"></i> Simpan Data
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

<script>
document.getElementById('barangSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const infoBox = document.getElementById('infoBarangBox');
    
    if (this.value !== "") {
        // Ambil data dari atribut data-
        const nama = selectedOption.getAttribute('data-nama');
        const stok = selectedOption.getAttribute('data-stok');
        const dipinjam = selectedOption.getAttribute('data-dipinjam');
        
        // Isi ke dalam box info
        document.getElementById('namaBarang').textContent = nama;
        document.getElementById('stokBarang').textContent = stok;
        document.getElementById('dipinjamBarang').textContent = dipinjam;
        
        // Munculkan box info
        infoBox.classList.remove('hidden');
    } else {
        // Sembunyikan jika tidak ada barang dipilih
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

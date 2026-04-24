<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $sql_kategori = mysqli_query($conn, "SELECT * FROM kategori");
    if (!$sql_kategori) {
        die("Query kategori error: " . mysqli_error($conn));
    }
    $create_message = "";
    $message_type = "";

    if (isset($_POST["tambah_data"])) {
    $kode_inventaris = $_POST["kode_inventaris"];
    $kategori_id = $_POST["kategori_id"];
    $nama_barang = $_POST["nama_barang"];
    $merk = $_POST["merk"];
    $tipe = $_POST["tipe"];
    $spesifikasi = $_POST["spesifikasi"];
    $jumlah = $_POST["jumlah"];
    $kondisi = $_POST["kondisi"];
    $lokasi = $_POST["lokasi"];
    $tahun_perolehan = $_POST["tahun_perolehan"];
    $keterangan = $_POST["keterangan"];
    $tersedia = $_POST["tersedia"];

    $sql = "INSERT INTO barang (kode_inventaris, kategori_id, nama_barang, merk, tipe, spesifikasi, jumlah, kondisi, lokasi, tahun_perolehan, keterangan) VALUES ('$kode_inventaris', '$kategori_id', '$nama_barang', '$merk', '$tipe', '$spesifikasi', '$jumlah', '$kondisi', '$lokasi', '$tahun_perolehan', '$keterangan')";
    if (mysqli_query($conn, $sql)) {
        header("Location: create.php?create=success");
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
                    <h1 class="text-2xl font-semibold text gray-800">Tambah Barang</h1>
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
                            <h1 class="text-2xl font-semibold text-gray-800">Tambah Inventaris Barang</h1>
                            <p class="text-sm text-gray-500">Masukkan detail barang baru ke dalam sistem.</p>
                        </div>
                        <button onclick="bukaModal()" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i> Kategori Baru
                        </button>
                    </div>

                    <div class="bg-white rounded shadow-md border-t-4 border-[#3c8dbc]">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                            <i class="fas fa-box-open text-[#3c8dbc] mr-2"></i>
                            <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">Form Detail Barang</h3>
                        </div>

                        <form method="POST" autocomplete="off" class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Kode Inventaris</label>
                                    <input type="text" name="kode_inventaris" placeholder="Contoh: INV-001" required
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang</label>
                                    <input type="text" name="nama_barang" placeholder="Contoh: Laptop Dell" required
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                                    <select name="kategori_id" required
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php while ($k = mysqli_fetch_assoc($sql_kategori)): ?>
                                            <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Merk</label>
                                    <input type="text" name="merk" placeholder="Dell, HP, Lenovo..."
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipe</label>
                                    <input type="text" name="tipe" placeholder="Inspiron 15, Thinkpad..."
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah</label>
                                    <input type="number" name="jumlah" min="1" required placeholder="0"
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Kondisi</label>
                                    <select name="kondisi" required
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                        <option value="baik">Baik</option>
                                        <option value="cukup">Cukup</option>
                                        <option value="rusak">Rusak</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi</label>
                                    <input type="text" name="lokasi" placeholder="Contoh: Ruang 101"
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tahun Perolehan</label>
                                    <input type="number" name="tahun_perolehan" min="2015" max="2030" placeholder="2024"
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>
                            </div>

                            <div class="mt-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Spesifikasi</label>
                                    <textarea name="spesifikasi" rows="3" placeholder="Masukkan detail spesifikasi..."
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan Tambahan</label>
                                    <textarea name="keterangan" rows="2" placeholder="Catatan lainnya..."
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20"></textarea>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                                <a href="inventaris.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                </a>
                                <button type="submit" class="px-4 py-2 bg-[#3c8dbc] hover:bg-[#367fa9] text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
                                    <i class="fas fa-save mr-2"></i> Simpan Data Barang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

<div id="modalKategori" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h4 class="font-bold text-gray-700">Tambah Kategori Baru</h4>
            <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form action="proses_kategori.php" method="POST" class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="nama_kategori" placeholder="Elektronik, Furniture, dll..." required 
                       class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="tutupModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-semibold text-sm">Batal</button>
                <button type="submit" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded font-semibold text-sm shadow-sm transition">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModal() {
    document.getElementById('modalKategori').classList.remove('hidden');
}
function tutupModal() {
    document.getElementById('modalKategori').classList.add('hidden');
}
</script>
</body>
</html>
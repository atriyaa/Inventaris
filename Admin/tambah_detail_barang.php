<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $sql_barang = mysqli_query($conn, "SELECT * FROM barang");
    $create_message = "";
    $message_type = "";

    if (isset($_POST["tambah_detail_barang"])) {
    $id_barang = $_POST["id_barang"];
    $kode_unit = $_POST["kode_unit"];
    $kondisi = $_POST["kondisi"];
    $status = $_POST["status"];
    $lokasi_meja = $_POST["lokasi_meja"];
    $lokasi_ruang = $_POST["lokasi_ruang"];

    $sql = "INSERT INTO barang_detail (id_barang, kode_unit, kondisi, status, lokasi_meja, lokasi_ruang) VALUES ('$id_barang', '$kode_unit', '$kondisi', '$status', '$lokasi_meja', '$lokasi_ruang')";
    if (mysqli_query($conn, $sql)) {
        header("Location: tambah_detail_barang.php?create=success");
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
                    <h1 class="text-2xl font-semibold text gray-800">Tambah Detail Barang</h1>
                </div>
                <?php if (isset($_GET['tambah_data']) && $_GET['tambah_data'] == 'success'): ?>
                <div class="bg-green-500 text-white p-3 rounded mb-4 text-center shadow-sm" id="alert">
                    <i class="fas fa-check-circle mr-2"></i> Deatil Barang Berhasil Di Tambah!
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
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang</label>
                                    <select id="nama"  name="tersedia" required
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                    <option value="">-- Pilih Nama Barang --</option>
                                    <?php while ($k = mysqli_fetch_assoc($sql_barang)): ?>
                                        <option value="<?= $k['id_barang']; ?>"><?= $k['nama_barang']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div>
                                    <label id="kode"  class="block text-sm font-bold text-gray-700 mb-1">Kode Unit</label>
                                    <input type="text" name="kode_unit" placeholder="Contoh: " required
                                        class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi Meja</label>
                                    <input type="text" name="tipe" placeholder="Inspiron 15, Thinkpad..."
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                </div>
                            </div>
                            
                            <div class="mt-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Kondisi</label>
                                    <select name="kondisi" required
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                        <option value="">-- Pilih Kondisi --</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Rusak">Rusak</option>
                                        <option value="Perbaikan">Perbaikan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                                    <select name="status" required
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Tersedia">Tersedia</option>
                                        <option value="Tidak Tersedia">Tidak Tersedia</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                                    <select name="lokasi_ruang" required
                                            class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                        <option value="">-- Pilih Ruangan --</option>
                                        <option value="LAB MM">LAB MM</option>
                                        <option value="LAB JARKOM">LAB JARKOM</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                                <a href="inventaris.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                </a>
                                <button name="tambah_detail_barang"  type="submit" class="px-4 py-2 bg-[#3c8dbc] hover:bg-[#367fa9] text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
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

<>
function bukaModal() {
    document.getElementById('modalKategori').classList.remove('hidden');
}
function tutupModal() {
    document.getElementById('modalKategori').classList.add('hidden');
}
document.getElementById("kode").addEventListener("input", function() {
    let value = this.value.toUpperCase();
    let nama = document.getElementById("nama");

    if (value.includes("KOMP")) {
        nama.placeholder = "Contoh: Komputer, Laptop, CPU";
    } else if (value.includes("AUD")) {
        nama.placeholder = "Contoh: Speaker, Mic, Sound System";
    } else if (value.includes("LAB")) {
        nama.placeholder = "Contoh: Meja Lab, Kursi Lab";
    } else {
        nama.placeholder = "Contoh: ";
    }
});
</script>
</body>
</html>
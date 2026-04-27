<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    if (!isset($_GET['id'])) {
        die("ID tidak ditemukan");
    }

    $query = "SELECT * FROM barang WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        die("Data tidak ditemukan");
    }

    if (isset($_POST['update'])) {
        $id = $_GET['id'];
        $kode_inventaris = $_POST["kode_inventaris"];
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
 
        $update = "UPDATE barang SET kode_inventaris = '$kode_inventaris', nama_barang = '$nama_barang', merk = '$merk', tipe = '$tipe', spesifikasi = '$spesifikasi', jumlah = '$jumlah', kondisi = '$kondisi', lokasi = '$lokasi', tahun_perolehan = '$tahun_perolehan', keterangan = '$keterangan', tersedia = '$tersedia' WHERE id = $id";
        if (mysqli_query($conn, $update)) {
            header("Location: edit.php?edit=success");
            exit;
        } else {
            echo "Gagal update data";
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
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Selamat Datang, <span class="font-semibold"><?php echo htmlspecialchars($_SESSION["username"]); ?></span></p>
                    <h1 class="text-2xl font-semibold text-gray-800">Edit Data Barang</h1>
                    <nav class="text-sm text-gray-500 mt-1">
                        <a href="dashboard.php" class="hover:text-[#3c8dbc]">Dashboard</a> 
                        <span class="mx-1">></span> 
                        <span class="text-gray-400">Edit Barang</span>
                    </nav>
                </div>

                <?php if (isset($_GET['create']) && $_GET['create'] == 'success'): ?>
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Data berhasil diperbarui!</span>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded shadow-md border-t-4 border-[#3c8dbc]">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                        <i class="fas fa-edit text-[#3c8dbc] mr-2"></i>
                        <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">Formulir Pembaruan Inventaris</h3>
                    </div>

                    <form method="POST" autocomplete="off" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Kode Inventaris</label>
                                <input type="text" name="kode_inventaris" value="<?= $data['kode_inventaris']; ?>" required
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang</label>
                                <input type="text" name="nama_barang" value="<?= $data['nama_barang']; ?>" required
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Merk</label>
                                <input type="text" name="merk" value="<?= $data['merk']; ?>"
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tipe</label>
                                <input type="text" name="tipe" value="<?= $data['tipe']; ?>"
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah</label>
                                <input type="number" name="jumlah" value="<?= $data['jumlah']; ?>" required
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Kondisi</label>
                                <select name="kondisi" class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                    <option value="Baik" <?= $data['kondisi'] == 'Baik' ? 'selected' : ''; ?>>Baik</option>
                                    <option value="Cukup" <?= $data['kondisi'] == 'Cukup' ? 'selected' : ''; ?>>Cukup</option>
                                    <option value="Rusak" <?= $data['kondisi'] == 'Rusak' ? 'selected' : ''; ?>>Rusak</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi</label>
                                <input type="text" name="lokasi" value="<?= $data['lokasi']; ?>"
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tahun Perolehan</label>
                                <input type="text" name="tahun_perolehan" value="<?= $data['tahun_perolehan']; ?>"
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tersedia</label>
                                <select name="tersedia" class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                    <option value="1" <?= ($data['tersedia'] == 1) ? 'selected' : ''; ?>>Iya</option>
                                    <option value="0" <?= ($data['tersedia'] == 0) ? 'selected' : ''; ?>>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Spesifikasi</label>
                                <textarea name="spesifikasi" rows="2" class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20"><?= $data['spesifikasi']; ?></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan</label>
                                <textarea name="keterangan" rows="2" class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20"><?= $data['keterangan']; ?></textarea>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="dashboard.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-[#3c8dbc] hover:bg-[#367fa9] text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
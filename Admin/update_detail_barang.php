<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $id = $_GET['id_detail'];

    $query = "SELECT * FROM barang_detail WHERE id_detail = $id";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        die("Data tidak ditemukan");
    }

    if (isset($_POST['update'])) {
        $kode_unit = $_GET['kode_unit'];
        $kondisi = $_GET['kondisi'];
        $status = $_GET['status'];
        $lokasi_meja = $_GET['lokasi_meja'];
        $lokasi_ruang = $_GET['lokasi_ruang'];
 
        $update = "UPDATE barang_detail SET kode_unit = '$kode_unit', kondisi = '$kondisi', status = '$status', lokasi_meja = '$lokasi_meja', lokasi_ruang = '$lokasi_ruang' WHERE id_detail = $id";
        if (mysqli_query($conn, $update)) {
            header("Location: update_detail_barang.php?edit=success");
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
                        <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">Formulir Pembaruan Detail Inventaris</h3>
                    </div>

                    <form method="POST" autocomplete="off" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Kode Unit</label>
                                <input type="text" name="kode_unit" value="<?= $data['kode_unit']; ?>" required
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Kondisi</label>
                                <select name="kondisi" class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                    <option value="Baik" <?= $data['kondisi'] == 'Baik' ? 'selected' : ''; ?>>Baik</option>
                                    <option value="Rusak" <?= $data['kondisi'] == 'Rusak' ? 'selected' : ''; ?>>Cukup</option>
                                    <option value="Perbaikan" <?= $data['kondisi'] == 'Perbaikan' ? 'selected' : ''; ?>>Rusak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi Meja</label>
                                <input type="text" name="lokasi_meja" value="<?= $data['lokasi_meja']; ?>" required
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                    <option value="Tersedia" <?= $data['kondisi'] == 'Tersedia' ? 'selected' : '';?> >Tersedia</option>
                                    <option value=" Tidak Tersedia" <?= $data['kondisi'] == 'Tidak Tersedia' ? 'selected' : ''; ?> >Tidak Tersedia</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi Ruang</label>
                                <select name="lokasi_ruang" class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                    <option value="LAB MM" <?= $data['kondisi'] == 'LAB MM' ? 'selected' : ''; ?> >LAB MM</option>
                                    <option value="LAB JARKOM" <?= $data['kondisi'] == 'LAB JARKOM' ? 'selected' : ''; ?> >LAB JARKOM</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="detail_barang.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Batal
                            </a>
                            <button name="update"  type="submit" class="px-4 py-2 bg-[#3c8dbc] hover:bg-[#367fa9] text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
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
<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $id_barang = $_GET['id_barang'] ?? null;

    if (!$id_barang){
        die("ID barang tidak ditemukan.");
    }
    $id_barang = mysqli_real_escape_string($conn, $id_barang);

    $query = "
    SELECT *
    FROM barang
    WHERE id_barang = '$id_barang'
    ";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    if (!$data) {
        die("Barang tidak ditemukan.");
    }

    if (isset($_POST['update'])){
        $id_kategori = mysqli_real_escape_string($conn, $_POST['id_kategori']);
        $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
        $merk = mysqli_real_escape_string($conn, $_POST['merk']);
        $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
        $spesifikasi = mysqli_real_escape_string($conn, $_POST['spesifikasi']);
        $tersedia = mysqli_real_escape_string($conn, $_POST['tersedia']);
        
        $update = "
        UPDATE barang 
        SET 
        id_kategori = '$id_kategori',
    nama_barang = '$nama_barang',
    merk = '$merk',
    tipe = '$tipe',
    spesifikasi = '$spesifikasi',
    tersedia = '$tersedia'
    WHERE id_barang = '$id_barang'
    ";
    if (mysqli_query($conn, $update)){
        header("Location: inventaris.php?create=success");
        exit;
    } else {
            echo "Gagal Update";
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
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang</label>
                                <input type="text" name="nama_barang" value="<?= $data['nama_barang']; ?>" required
                                    class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Merk</label>
                                <input type="text" name="merk" value="<?= $data['merk']; ?>" required
                                class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tipe</label>
                                <input type="text" name="tipe" value="<?= $data['tipe']; ?>" required
                                class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Spesifikasi</label>
                                <input type="text" name="spesifikasi" value="<?= $data['spesifikasi']; ?>" required
                                class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tersedia</label>
                                <select name="tersedia" class="w-full border-gray-300 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                    <option value="Tersedia" <?= $data['tersedia'] == 'Tersedia' ? 'selected' : '';?> >Tersedia</option>
                                    <option value=" Tidak Tersedia" <?= $data['tersedia'] == 'Tidak Tersedia' ? 'selected' : ''; ?> >Tidak Tersedia</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="inventaris.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
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
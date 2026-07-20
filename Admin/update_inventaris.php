<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();

    $id_barang = $_GET['id_barang'] ?? null;

    if (!$id_barang) {
        die("ID barang tidak ditemukan.");
    }
    $id_barang = mysqli_real_escape_string($conn, $id_barang);

    // 1. Ambil data barang yang akan diedit
    $query = "SELECT * FROM barang WHERE id_barang = '$id_barang'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        die("Barang tidak ditemukan.");
    }

    // 2. Ambil data daftar kategori untuk dropdown
    $sql_kategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC");

    // 3. Proses Update Data
    if (isset($_POST['update'])) {
        $id_kategori = $_POST['id_kategori'] ?? '';
        $nama_barang = $_POST['nama_barang'] ?? '';
        $merk        = $_POST['merk'] ?? '';
        $tipe        = $_POST['tipe'] ?? '';
        $spesifikasi = $_POST['spesifikasi'] ?? '';
        $tersedia    = $_POST['tersedia'] ?? '';

        // Menggunakan Prepared Statement agar aman dari SQL Injection & error tipe data
        $stmt = mysqli_prepare($conn, "UPDATE barang SET 
                    id_kategori = ?, 
                    nama_barang = ?, 
                    merk = ?, 
                    tipe = ?, 
                    spesifikasi = ?, 
                    tersedia = ? 
                WHERE id_barang = ?");

        mysqli_stmt_bind_param($stmt, "isssssi", $id_kategori, $nama_barang, $merk, $tipe, $spesifikasi, $tersedia, $id_barang);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: inventaris.php?update=success");
            exit;
        } else {
            $error_message = "Gagal memperbarui data: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style_form.css">
    <title>InventarisApp - Edit Barang</title>
    <style>
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
        
        <div class="flex-1 flex flex-col overflow-y-auto">
            <?php include '../include/header_hlm.php'; ?>
            
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Selamat Datang, <span class="font-semibold"><?= htmlspecialchars($_SESSION["username"] ?? 'User'); ?></span></p>
                    <h1 class="text-2xl font-semibold text-gray-800">Edit Data Barang</h1>
                    <nav class="text-sm text-gray-500 mt-1">
                        <a href="dashboard.php" class="hover:text-[#3c8dbc]">Dashboard</a> 
                        <span class="mx-1">></span> 
                        <a href="inventaris.php" class="hover:text-[#3c8dbc]">Inventaris</a>
                        <span class="mx-1">></span> 
                        <span class="text-gray-400">Edit Barang</span>
                    </nav>
                </div>

                <?php if (isset($error_message)): ?>
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?= $error_message; ?></span>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded shadow-md border-t-4 border-[#3c8dbc]">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                        <i class="fas fa-edit text-[#3c8dbc] mr-2"></i>
                        <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">Formulir Pembaruan Detail Inventaris</h3>
                    </div>

                    <form method="POST" autocomplete="off" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <!-- 1. Kategori (Input yang sebelumnya hilang) -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                                <select name="id_kategori" required class="w-full border border-gray-300 p-2 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php while ($k = mysqli_fetch_assoc($sql_kategori)): ?>
                                        <option value="<?= $k['id_kategori']; ?>" <?= ($data['id_kategori'] == $k['id_kategori']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($k['nama_kategori']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- 2. Nama Barang -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang']); ?>" required
                                    class="w-full border border-gray-300 p-2 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <!-- 3. Merk -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Merk</label>
                                <input type="text" name="merk" value="<?= htmlspecialchars($data['merk']); ?>"
                                    class="w-full border border-gray-300 p-2 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <!-- 4. Tipe -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tipe</label>
                                <input type="text" name="tipe" value="<?= htmlspecialchars($data['tipe']); ?>"
                                    class="w-full border border-gray-300 p-2 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                            </div>

                            <!-- 5. Status Tersedia -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Status Ketersediaan <span class="text-red-500">*</span></label>
                                <select name="tersedia" required class="w-full border border-gray-300 p-2 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20">
                                    <option value="Iya" <?= ($data['tersedia'] == 'Iya' || $data['tersedia'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia (Iya)</option>
                                    <option value="Tidak" <?= ($data['tersedia'] == 'Tidak' || $data['tersedia'] == 'Tidak Tersedia') ? 'selected' : ''; ?>>Tidak Tersedia (Tidak)</option>
                                </select>
                            </div>

                        </div>

                        <!-- 6. Spesifikasi (Dibuat Textarea agar lebih leluasa) -->
                        <div class="mt-6">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Spesifikasi</label>
                            <textarea name="spesifikasi" rows="3" class="w-full border border-gray-300 p-2 rounded shadow-sm focus:border-[#3c8dbc] focus:ring focus:ring-[#3c8dbc]/20"><?= htmlspecialchars($data['spesifikasi']); ?></textarea>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="inventaris.php" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm font-semibold transition flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Batal
                            </a>
                            <button name="update" type="submit" class="px-4 py-2 bg-[#3c8dbc] hover:bg-[#367fa9] text-white rounded text-sm font-semibold shadow-sm transition flex items-center">
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
<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    if (!isset($_GET['id'])) {
        die("ID tidak ditemukan");
    }
    $id = (int) $_GET['id'];

    $query = "SELECT * FROM barang WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        die("Data tidak ditemukan");
    }

    if (isset($_POST['update'])) {
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
            header("Location: dashboard.php?pesan=edit");
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
    <title>Edit Barang</title>
        <link rel="stylesheet" href="../assets/style.css">
    <style>
        body{
            background-color: #f4f7fa;
            background-image: radial-gradient(#dce4f0 1.5px, transparent 1.5px);
            background-size: 25px 25px;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 30px;
        }

        .header-section {
            max-width: 900px;
            margin: 0 auto 20px auto; /* Margin auto agar sejajar dengan card bawah */
            text-align: left;
        }

        .header-section h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }

        .header-section p {
            margin: 5px 0 5px;
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Tombol + Tambah Kategori (Gaya Sketsa kamu) */
        .btn-quick-kategori {
            display: inline-block;
            background: #ffffff;
            color: #3498db;
            border: 2px solid #3498db;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            font-size: 13px;
            transition: 0.3s;
            cursor: pointer;
        }

        .btn-quick-kategori:hover {
            background: #3498db;
            color: white;
        }
        a {
            text-decoration: none;
        }
        .content-wrapper {
            padding: 50px;
        }
        /* Container Utama */
        .page-header {
            margin-bottom: 30px;
        }
        .card-form {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 800px; 
            margin: 20px auto;
            padding: 25px; 
            border-top: 4px solid #3498db;
            border: 1px solid rgba(220, 223, 230, 0.7);
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 5px; 
            font-size: 13px; 
        }

        .input-group input, 
        .input-group select, 
        .input-group textarea {
            width: 100%;
            padding: 10px 12px; 
            border: 1px solid #dcdfe6;
            border-radius: 6px; 
            font-size: 13px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            background: #fdfdfd;
        }

        .input-group input:focus, .input-group select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.15);
        }

        .btn-tambah-kat {
            background: #e1f0fa; /* Biru sangat muda */
            color: #3498db;
            border: 1px solid #b3d7f1;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 5px;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-tambah-kat:hover {
            background: #d1e9f7;
        }

        /* --- 5. Judul Halaman (Breadcrumb) --- */
        .page-header h3 {
            color: #2c3e50;
            font-size: 18px; /* Kecilkan sedikit */
            margin: 0 0 5px;
        }
        .page-header p {
            font-size: 12px;
            color: #bdc3c7;
            margin: 0 0 20px;
        }

        .btn-simpan, .btn-batal {
            min-width: 150px; /* Biar tombol punya lebar yang sama dan proporsional */
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Tombol */
        .form-actions {
            display: flex;
            justify-content: center; 
            align-items: center;
            gap: 20px; /* Jarak antar tombol */
            margin-top: 20px;
            gap: 15px;
        }

        .btn-simpan {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-simpan:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-batal {
            background: #f8f9fa;
            color: #636e72;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 14px;
            border: 1px solid #dcdfe6;
        }

        .btn-batal:hover {
            background: #eee;
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
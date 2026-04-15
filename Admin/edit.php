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
<body>
    <div class="content-wrapper">
        <div class="form-container">
        <div class="header-section">
            <p>Selamat Datang, <?php echo $_SESSION["username"]; ?></p>
            <h3><i class="fa fa-plus-circle"></i> Edit Barang</h3>
            <p><a href="dashboard.php">Dashboard</a> <a href="edit.php"> > Edit Barang</a></p>
        </div>
        <div class="card-form">
            <?php if (isset($_GET['create']) && $_GET['create'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show notif-fix text-center">
            Data berhasil diperbarui!
            </div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-grid">
                    <div class="input-group">
                        <Label>Kode Inventaris</Label>
                        <input type="text" name="kode_inventaris" value="<?=  $data['kode_inventaris']; ?>"required>
                    </div> 
        
                    <div class="input-group">
                        <Label>Nama Barang</Label>
                        <input type="text" name="nama_barang" value="<?=  $data['nama_barang']; ?>"required>
                    </div>

                    <div class="input-group">
                        <label>Merk</label>
                        <input type="text" name="merk" value="<?=  $data['merk']; ?>">
                    </div>
        
                    <div class="input-group">
                        <label>Tipe</label>
                        <input type="text" name="tipe" value="<?=  $data['tipe']; ?>">
                    </div>
        
                    <div class="input-group full-width">
                        <label>Spesifikasi</label>
                        <input type="text" name="spesifikasi" value="<?=  $data['spesifikasi']; ?>">
                    </div>
        
                    <div class="input-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" value="<?=  $data['jumlah']; ?>"required>
                    </div>
        
                    <div class="input-group">
                        <label>Kondisi</label>
                        <select name="kondisi">
                            <option value="Baik"
                                <?= $data['kondisi'] == 'Baik' ? 'selected' : ''; ?>>
                                Baik
                            </option>
                            <option value="Cukup"
                                <?= $data['kondisi'] == 'Cukup' ? 'selected' : ''; ?>>
                                Cukup
                            </option>
                            <option value="Rusak"
                                <?= $data['kondisi'] == 'Rusak' ? 'selected' : ''; ?>>
                                Rusak
                            </option>
                        </select>
                    </div>
        
                    <div class="input-group">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" value="<?=  $data['lokasi']; ?>">
                    </div>
        
                    <div class="input-group">
                        <label>Tahun Perolehan</label>
                        <input type="text" name="tahun_perolehan" value="<?=  $data['tahun_perolehan']; ?>">
                    </div>
        
                    <div class="input-group full-width">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" value="<?= $data['keterangan']; ?>">
                    </div>
        
                    <div class="input-group">
                        <label for="">Tersedia</label>
                        <select name="tersedia">
                            <option value="1" <?=  ($data['tersedia'] == 1) ? 'selected' : '';?>">Iya</option>
                            <option value="0" <?=  ($data['tersedia'] == 0) ? 'selected' : '';?>">Tidak</option> 
                        </select>
                    </div>
        
                    <div class="form-actions">
                        <button type="submit" class="btn-simpan">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </a>
                    </div>
                    <div class="form-actions">
                        <a href="dashboard.php" class="btn-batal">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</body>
</html>
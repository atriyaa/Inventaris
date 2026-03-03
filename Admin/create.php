<?php
    require_once __DIR__ . "/../config/database.php";
    $create_message = "";
    $message_type = "";
    session_start();
        if (isset($_POST["tambah_data"])){
        $kode_inventaris = $_POST["kode_inventaris"];
        $nama_barang = $_POST["nama_barang"];
        $kategori = $_POST["kategori"];
        $merk = $_POST["merk"];
        $tipe = $_POST["tipe"];
        $spesifikasi = $_POST["spesifikasi"];
        $jumlah = $_POST["jumlah"];
        $kondisi = $_POST["kondisi"];
        $lokasi = $_POST["lokasi"];
        $tahun_perolehan = $_POST["tahun_perolehan"];
        $keterangan = $_POST["keterangan"];
        $tersedia = $_POST["tersedia"];

        $sql = "INSERT INTO barang (kode_inventaris, nama_barang, kategori, merk, tipe, spesifikasi, jumlah, kondisi, lokasi, tahun_perolehan, keterangan) VALUES ('$kode_inventaris', '$nama_barang', '$kategori','$merk', '$tipe', '$spesifikasi', '$jumlah', '$kondisi', '$lokasi', '$tahun_perolehan', '$keterangan')";
        if ($conn->query($sql)) {
            $create_message = "Data berhasil ditambahkan";
            $message_type = "success";
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
    <title>Tambah Data Inventaris</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            background: #f1f5f9;
            min-height: 100vh;
            padding: 20px;
        }
        .welcome-header {
            text-align: center;
            color: #1e293b;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="welcome-header">
        <h4>Selamat Datang, <?php echo $_SESSION["username"]; ?></h4>
    </div>
    
    <div class="form-container">
        <div class="form-header">
            <h2>Form Tambah Inventaris</h2>
            <p>Silakan lengkapi data inventaris baru</p>
        </div>
        
        <?php if ($create_message): ?>
            <div class="form-message <?php echo $message_type; ?>">
                <?php echo $create_message; ?>
            </div>
        <?php endif; ?>
        
        <form autocomplete="off"  method="POST" class="form-tambah">
            <div class="form-group">
                <label for="kode_inventaris">Kode Inventaris</label>
                <input type="text" name="kode_inventaris" placeholder="Contoh: INV-001" required>
            </div>
    
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" placeholder="Contoh: Laptop Dell" required>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="kategori" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Alat Tulis">Alat Tulis</option>
                    <option value="Lab">Lab</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
    
            <div class="form-group">
                <label for="merk">Merk</label>
                <input type="text" name="merk" placeholder="Contoh: Dell, HP, Lenovo">
            </div>
    
            <div class="form-group">
                <label for="tipe">Tipe</label>
                <input type="text" name="tipe" placeholder="Contoh: Inspiron 15">
            </div>
    
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" min="1" placeholder="Jumlah barang" required>
            </div>

            <div class="form-group">
                <label for="kondisi">Kondisi</label>
                <select name="kondisi" required>
                    <option value="baik">Baik</option>
                    <option value="cukup">Cukup</option>
                    <option value="rusak">Rusak</option>
                </select>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" placeholder="Contoh: Ruang 101">
            </div>
    
            <div class="form-group">
                <label for="tahun_perolehan">Tahun Perolehan</label>
                <input type="number" name="tahun_perolehan" min="2015" max="2030" placeholder="Contoh: 2024">
            </div>

            <div class="form-group full-width">
                <label for="spesifikasi">Spesifikasi</label>
                <textarea name="spesifikasi" placeholder="Masukkan spesifikasi detail barang..."></textarea>
            </div>

            <div class="form-group full-width">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" placeholder="Masukkan keterangan tambahan jika diperlukan..."></textarea>
            </div>

            <div class="form-group">
                <label for="tersedia">Tersedia</label>
                <select name="tersedia">
                    <option value="1">Iya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
    
            <button type="submit" name="tambah_data" class="btn-submit">Tambah Data Inventaris</button>

            <div class="form-back">
                <a href="dashboard.php">← Kembali ke Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>

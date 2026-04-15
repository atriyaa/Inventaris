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
    <title>silahkan Tambah Data Inventaris</title>
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
            margin: 0 auto 20px auto;
        }

        .header-section h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }

        .header-section p {
            margin: 5px 0 15px;
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
        /* Styling Modal */
        .modal {
            display: none; /* Sembunyi */
            position: fixed;
            z-index: 2000; /* Harus lebih tinggi dari sidebar/header */
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5); /* Background gelap transparan */
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            width: 350px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: zoomIn 0.3s ease;
        }
        .modal-header {
            padding: 15px;
            background: #3498db;
            color: white;
            display: flex;
            justify-content: space-between;
        }
        .modal-body { padding: 20px; }
        .modal-footer { padding: 15px; text-align: right; border-top: 1px solid #eee; }
        
        .close { cursor: pointer; font-size: 20px; }
        
        @keyframes zoomIn {
            from { transform: scale(0.7); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="header-section">
            <p>Selamat Datang, <?php echo $_SESSION["username"]; ?></p>
            <h3><i class="fa fa-plus-circle"></i> Tambah Data Barang</h3>
            <p><a href="dashboard.php">Dashboard</a> <a href="create.php"> > Tambah Barang</a></p>
            <button type="button" onclick="bukaModal()" class="btn-quick-kategori">+ Tambah Kategori</button>
        </div>
        <div class="card-form">
            <?php if (isset($_GET['create']) && $_GET['create'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show notif-fix text-center">
            Data berhasil ditambahkan!
            </div>
            <?php endif; ?>
            <form autocomplete="off"  method="POST">
                <div class="form-grid">
                    <div class="input-group">
                        <label for="kode_inventaris">Kode Inventaris</label>
                        <input type="text" name="kode_inventaris" placeholder="Contoh: INV-001" required>
                    </div>
                    <div class="input-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" name="nama_barang" placeholder="Contoh: Laptop Dell" required>
                    </div>
        
                    <div class="input-group">
                        <label for="kategori">Kategori</label>
                        <div class="select-wrapper">
                            <select name="kategori_id">
                                <option value="">- - Pilih Kategori - -</option>
                                <?php while ($k = mysqli_fetch_assoc($sql_kategori)){
                                    echo "<option value='".$k['id']."'>".$k['nama_kategori']."</option>";
                                    }?>
                            </select>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="merk">Merk</label>
                        <input type="text" name="merk" placeholder="Contoh: Dell, HP, Lenovo">
                    </div>
            
                    <div class="input-group">
                        <label for="tipe">Tipe</label>
                        <input type="text" name="tipe" placeholder="Contoh: Inspiron 15">
                    </div>
            
                    <div class="input-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" min="1" placeholder="Jumlah barang" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="kondisi">Kondisi</label>
                        <select name="kondisi" required>
                            <option value="baik">Baik</option>
                            <option value="cukup">Cukup</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" name="lokasi" placeholder="Contoh: Ruang 101">
                    </div>
                    
                    <div class="input-group">
                        <label for="tahun_perolehan">Tahun Perolehan</label>
                        <input type="number" name="tahun_perolehan" min="2015" max="2030" placeholder="Contoh: 2024">
                    </div>
                    
                    <div class="input-group">
                        <label for="tersedia">Tersedia</label>
                        <select name="tersedia">
                            <option value="1">Iya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    
                    <div class="input-group full-width">
                        <label for="spesifikasi">Spesifikasi</label>
                        <textarea name="spesifikasi" placeholder="Masukkan spesifikasi detail barang..."></textarea>
                    </div>
                    
                    <div class="input-group full-width">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" placeholder="Masukkan keterangan tambahan jika diperlukan..."></textarea>
                    </div>
                    <div class="form-actions">
                        <a href="dashboard.php" class="btn-batal">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn-simpan">
                            <i class="fa fa-save"></i> Simpan Data
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="modalKategori" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Tambah Kategori Baru</h4>
                <span class="close" onclick="tutupModal()">&times;</span>
            </div>
            <form action="proses_kategori.php" method="POST">
                <div class="modal-body">
                    <input type="text" name="nama_kategori" placeholder="Masukkan nama kategori..." required 
                        style="width: 90%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-tambah">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function bukaModal() { document.getElementById("modalKategori").style.display = "block"; }
    function tutupModal() { document.getElementById("modalKategori").style.display = "none"; }
    // Tutup jika klik di luar kotak putih
    window.onclick = function(event) {
        if (event.target == document.getElementById("modalKategori")) { tutupModal(); }
    }
    </script>
</body>
</html>

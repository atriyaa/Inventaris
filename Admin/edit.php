<?php
    require_once __DIR__ . "/../config/database.php";

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
            header("Location: dashboard.php");
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
    <div class="form-container">
        <div class="form-header">
            <h2>Edit Barang</h2>
        </div>

        <form method="POST" class="form-tambah">
            <div class="form-group">
                <Label>Kode Inventaris</Label>
                <input type="text" name="kode_inventaris" value="<?=  $data['kode_inventaris']; ?>"required>
            </div> 

            <div class="form-group">
                <Label>Nama Barang</Label>
                <input type="text" name="nama_barang" value="<?=  $data['nama_barang']; ?>"required>
            </div>

            <div class="form-group">
                <label>Merk</label>
                <input type="text" name="merk" value="<?=  $data['merk']; ?>">
            </div>

            <div class="form-group">
                <label>Tipe</label>
                <input type="text" name="tipe" value="<?=  $data['tipe']; ?>">
            </div>

            <div class="form-group full-width">
                <label>Spesifikasi</label>
                <input type="text" name="spesifikasi" value="<?=  $data['spesifikasi']; ?>">
            </div>

            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" value="<?=  $data['jumlah']; ?>"required>
            </div>

            <div class="form-group">
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

            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" value="<?=  $data['lokasi']; ?>">
            </div>

            <div class="form-group">
                <label>Tahun Perolehan</label>
                <input type="text" name="tahun_perolehan" value="<?=  $data['tahun_perolehan']; ?>">
            </div>

            <div class="form-group full-width">
                <label>Keterangan</label>
                <input type="text" name="keterangan" value="<?= $data['keterangan']; ?>">
            </div>

            <div class="form-group">
                <label for="">Tersedia</label>
                <select name="tersedia">
                    <option value="1" <?=  ($data['tersedia'] == 1) ? 'selected' : '';?>">Iya</option>
                    <option value="0" <?=  ($data['tersedia'] == 0) ? 'selected' : '';?>">Tidak</option> 
                </select>
            </div>

            <button type="submit" name="update" class="btn-submit">Update</button>

            <div class="form-back">
                <a href="dashboard.php">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
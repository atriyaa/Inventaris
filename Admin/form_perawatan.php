<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $sql_nama_barang = mysqli_query($conn, "SELECT id,nama_barang FROM barang");
    if (!$sql_nama_barang) {
        die("Query kategori error: " . mysqli_error($conn));
    }
    $create_message = "";
    $message_type = "";

    if (isset($_POST["tambah_data"])) {
    $nama_barang = $_POST["nama_barang"];
    $tanggal_perbaikan = $_POST["tanggal_perbaikan"];
    $deskripsi = $_POST["deskripsi"];

    $sql = "INSERT INTO perawatan(tanggal_perbaikan, deskripsi, id) VALUES ('$tanggal_perbaikan', '$deskripsi', '$nama_barang')";
    if (mysqli_query($conn, $sql)) {
        header("Location: perawatan.php?create=success");
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
    <link rel="stylesheet" href="../assets/style2.css">
    <link rel="stylesheet" href="../assets/style_form.css">

</head>
<body>
    <div class="content-wrapper">
        <div class="header-section">
            <p>Selamat Datang, <?php echo $_SESSION["username"]; ?></p>
            <h3><i class="fa fa-plus-circle">Tambah Data Perawatan</i></h3>
            <p><a href="dashboard.php">Dashboard</a> <a href="form_perawatan.php"> > Tambah Data Perawatan</a></p>
        </div>
        <div class="card-form">
            <form autocomplete="off"  method="POST">
                <div class="form-grid">
                    <div class="input-group">
                        <label for="nama_barang">Nama Barang</label>
                        <div class="select-wrapper">
                            <select name="nama_barang">
                                <option value="">- - Pilih Barang - -</option>
                                <?php while ($k = mysqli_fetch_assoc($sql_nama_barang)) {
                                    echo "<option value='".$k['id']."'>".$k['nama_barang']."</option>";
                                    }?>
                            </select>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="Tanggal_perbaikan">Tanggal</label>
                        <input type="date" name="tanggal_perbaikan" required>
                    </div>
                    <div class="input-group full-width">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" placeholder="Masukkan deskripsi perawatan..."></textarea>
                    </div>
                    <div class="form-actions">
                        <a href="perawatan.php" class="btn-batal">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" name='tambah_data' class="btn-simpan">
                            <i class="fa fa-save"></i> Simpan Data
                        </button>
                    </div>
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

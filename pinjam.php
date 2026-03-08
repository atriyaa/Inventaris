<?php
require_once "config/database.php";
session_start();

if (isset($_POST["pinjam"])) {
    $nama_peminjam = $_POST['nama_peminjam'];
    $keperluan = $_POST['keperluan'];
    $jumlah = $_POST['jumlah'];
    $barang_id = $_POST['barang_id'];

    $sql =  "INSERT INTO peminjaman (nama_peminjam, keperluan, jumlah, barang_id) VALUES ('$nama_peminjam', '$keperluan', '$jumlah', '$barang_id')";
    if (mysqli_query($conn, $sql)) {
        $create_message = "Data berhasil ditambahkan";
        $message_type = "success";
    } else {
        $create_message = "Data gagal ditambahkan";
        $message_type = "error";
    }
}
$query = mysqli_query($conn, "SELECT barang.*, IFNULL(SUM(peminjaman.jumlah),0) AS sedang_dipinjam FROM barang
LEFT JOIN peminjaman ON barang.id = peminjaman.barang_id AND peminjaman.status='dipinjam' GROUP BY barang.id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="body">
    <?php $page = basename($_SERVER['PHP_SELF']); ?>
    <div class="sidebar" id="sidebar">
        <h2 class="logo">INVENTARIS LABORATORIUM<br>INFORMATIKA</h2>
        <div class="menu-group">
            <p class="menu-title">MAIN</p>
            <ul>
                <li><a href="Admin/dashboard.php" class="menu-item <?=  $page=='dashboard.php'?'active':''?>">Dashboard</a></li>
                <li><a href="../pinjam.php" class="menu-item  <?=  $page=='pinjam.php'?'active':''?>">Pinjam</a></li>
                <li><a href="peminjaman.php" class="menu-item  <?=  $page=='peminjaman.php'?'active':''?>">Peminjaman Aktif</a></li>
                <li><a href="../history_peminjaman.php" class="menu-item  <?=  $page=='history_peminjaman.php'?'active':''?>">History Peminjaman</a></li>
            </ul>
        </div>
        <div class="menu-group">
            <p class="menu-title">TEAMS</p>
            <!-- nanti ambil di database admin -->
        </div>
        <div class="logout">
            <a href="../logout.php" class="menu-item logout">Logout</a>
        </div>
    </div>
    <div class="main" id="main">
        <div class="header">
            <div class="button_dash">
                <button id="toggleSidebar">☰</button>
                <h1>Pinjam Barang</h1>
            </div>
        </div>
    <div class="form-container">
        <div class="form-header">
            <div class="welcome-header">
                <h4>Selamat Datang ,<?php echo $_SESSION["username"];?></h4>
                <h3>Form Pinjam Inventaris</h3>
                <p class="sub">Silahkan lengkapi data pinjam</p>
            </div>
        </div>
        <div class="info-barang hidden-info" id="infoBarangBox">
            <h5>Info Barang</h5>
            <div class="info-card">
                <p><b>Nama Barang :</b><span id="namaBarang">-</span></p>
                <p><b>Stok :</b><span id="stokBarang">-</span></p>
                <p><b>Sedang Dipinjam :</b><span id="dipinjamBarang">-</span></p>
            </div>
        </div>
        <div class="form-card">
            <form autocomplete="off"  method="POST" class="form-tambah">
                <div class="form-group">
                    <label for="pilih barang">Pilih Barang</label>
                    <select  id="barangSelect" name="barang_id" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php while($row = mysqli_fetch_assoc($query)) { 
                            $stok_tersedia = $row['jumlah'] - $row['sedang_dipinjam']; 
                        ?>
                        <option value="<?php echo $row['id']; ?>"
                        data-nama="<?php echo $row['nama_barang']; ?>"
                        data-stok="<?php echo $stok_tersedia; ?>"
                        data-dipinjam="<?php echo $row['sedang_dipinjam']; ?>">
                        <?php echo $row['nama_barang']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" required>
                </div>
                <div class="form-group">
                    <label for="nama_peminjam">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" required>
                </div>
                <div class="form-group">
                    <label for="keperluan">Keperluan</label>
                    <input type="text" name="keperluan" required>
                </div>
                
                <div class="tombol-submit">
                    <button type="submit" name="pinjam" class="btn-tambah-inventaris">Pinjam</button>
                </div>
                <div class="form-back">
                    <a href="Admin/dashboard.php">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
<script>
    const toggle = document.getElementById("toggleSidebar");
    const sidebar = document.querySelector(".sidebar");
    const main = document.querySelector(".main")

    toggle.onclick = function() {
    sidebar.classList.toggle("close");
    main.classList.toggle("full");
    }
    document.addEventListener("DOMContentLoaded", function() { 
        const barangSelect = document.getElementById("barangSelect");
        const infoBox = document.getElementById("infoBarangBox");

        const namaBarang = document.getElementById("namaBarang");
        const stokBarang = document.getElementById("stokBarang");
        const dipinjamBarang = document.getElementById("dipinjamBarang");

        barangSelect.addEventListener("change", function() {
            console.log(barangSelect);
            const selected = this.options[this.selectedIndex];

            const nama = selected.getAttribute("data-nama");
            const stok = selected.getAttribute("data-stok");
            const dipinjam = selected.getAttribute("data-dipinjam");

            if(nama) {
                infoBox.classList.remove("hidden-info");

                namaBarang.textContent = nama;
                stokBarang.textContent = stok;
                dipinjamBarang.textContent = dipinjam;
            } else {
                infoBox.classList.add("hidden-info");
            }
        });
    });
</script>
</body>
</html>  
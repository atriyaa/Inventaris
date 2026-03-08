<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();

$query = mysqli_query($conn,"
SELECT peminjaman.*, barang.nama_barang
FROM peminjaman
JOIN barang ON peminjaman.barang_id = barang.nama_barang
WHERE peminjaman.status='dipinjam'
ORDER BY peminjaman.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/style.css">
    <meta charset="UTF-8">
    <title>Inventaris Laboratorium Informatika</title>
</head>
<body class="body">
    <?php $page = basename($_SERVER['PHP_SELF']); ?>
    <div class="sidebar" id="sidebar">
        <h2 class="logo">INVENTARIS LABORATORIUM<br>INFORMATIKA</h2>
        <div class="menu-group">
            <p class="menu-title">MAIN</p>
            <ul>
                <li><a href="dashboard.php" class="menu-item <?=  $page=='dashboard.php'?'active':''?>">Dashboard</a></li>
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
            <a href="logout.php" class="menu-item logout">Logout</a>
        </div>
    </div>
    <div class="main" id="main">
        <div class="header">
            <div class="button_dash">
                <button id="toggleSidebar">☰</button>
                <h1>Peminjaman Aktif</h1>
            </div>
            <div class="user">Selamat Datang, <br><?php echo $_SESSION["username"];?></div>
        </div>
            <div class="form-container">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Nama Peminjam</th>
                        <th>Jumlah</th>
                        <th>Keperluan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Aksi</th>
                    </tr>
                        <?php
                        $no = 1;
                        while($row = mysqli_fetch_assoc($query)){
                        ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama_barang']; ?></td>
                        <td><?= $row['nama_peminjam']; ?></td>
                        <td><?= $row['jumlah']; ?></td>
                        <td><?= $row['keperluan']; ?></td>
                        <td><?= $row['tanggal_pinjam']; ?></td> 
                        <td>
                            <a class="btn"  href="kembalikan.php?id=<?= $row['id']; ?>">
                                <button>Kembalikan</button>
                            </a>
                        </td>
                        <?php } ?>
                    </tr>
                </table>
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
    src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js";
    document.getElementById("kategoriSelect").addEventListener("change", function () {
        let value = this.value;
        if (value != "") {
            window.location.href = "dashboard.php?filter=" + value;
        }
    });
</script>
</body>
</html>
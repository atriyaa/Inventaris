<?php
    require_once __DIR__ . "/config/database.php";
    session_start();

$sql = "
SELECT peminjaman.*, barang.nama_barang
FROM peminjaman
JOIN barang ON peminjaman.barang_id = barang.id
ORDER BY peminjaman.id DESC
";
$result = $conn->query($sql);

// cek error
if (!$result) {
    die("Query error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/style.css">
    <meta charset="UTF-8">
    <title>Inventaris Laboratorium Informatika</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fb;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #c9c2c2;
            text-align: center;
        }
        th {
            background-color: #14466c;
            color: black;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .status-kembali {
            color: green;
            font-weight: bold;
        }
        .status-pinjam {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body class="body">
    <div class="container">
        <?php $page = basename($_SERVER['PHP_SELF']); ?>
        <div class="sidebar" id="sidebar">
            <h2 class="logo">INVENTARIS LABORATORIUM<br>INFORMATIKA</h2>
            <div class="menu-group">
                <p class="menu-title">MAIN</p>
                <ul>
                    <li><a href="Admin/dashboard.php" class="menu-item <?=  $page=='dashboard.php'?'active':''?>">Dashboard</a></li>
                    <li><a href="../pinjam.php" class="menu-item  <?=  $page=='pinjam.php'?'active':''?>">Pinjam</a></li>
                    <li><a href="Admin/peminjaman.php" class="menu-item  <?=  $page=='peminjaman.php'?'active':''?>">Peminjaman Aktif</a></li>
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
                    <h1>History Peminjaman</h1>
                </div>
                <div class="user">Selamat Datang, <br><?php echo $_SESSION["username"];?></div>
            </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr class="table-header">
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Nama Peminjam</th>
                                <th>Jumlah</th>
                                <th>Keperluan</th>
                                <th>Tanggal Pinjam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                            <?php
                            if ($result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                            ?>
                        <tr class="table-row">
                            <td><?= $no++; ?></td>
                            <td><?= $row['nama_barang']; ?></td>
                            <td><?= $row['nama_peminjam']; ?></td>
                            <td><?= $row['jumlah']; ?></td>
                            <td><?= $row['keperluan']; ?></td>
                            <td><?= $row['tanggal_pinjam']; ?></td> 
                            <td>
                                <?php 
                                if ($row['status'] == 'dikembalikan') {
                                    echo "<span class='status-kembali'>Kembali</span>";
                                } else {
                                    echo "<span class='status-pinjam'>Dipinjam</span>";
                                }
                                ?>
                            </td>
                        </tr>
                        <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7'>Tidak ada data peminjaman</td></tr>";
                    }
                    ?>
                    </table>
                </div>
            </div>
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
<?php
$conn->close();
?>
<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    $pesan = '';
    if (isset($_GET['pinjam']) && $_GET['pinjam'] === 'success') {
        $pesan = "Peminjaman berhasil 👍";
    }
    if (!isset($_SESSION['admin'])) {
        header("Location: ../login.php");
        exit;
        }
        $lab = $_GET['lab'] ?? null;
        $filter = $_GET['filter'] ?? 'all';

    $where = [];

    if ($filter != 'all') {
        $where[] = "barang.kategori_id = '$filter'";
    }

    if ($lab == 'lab_mm') {
        $where[] = "barang.lokasi = 'LAB MM'";
    } elseif ($lab == 'lab_jarkom') {
        $where[] = "barang.lokasi = 'LAB Jarkom'";
    }

    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }

    $query = "
        SELECT barang.*, kategori.nama_kategori
        FROM barang
        JOIN kategori ON barang.kategori_id = kategori.id
        $where_sql 
        ORDER BY barang.id DESC
    ";
    $result = mysqli_query($conn, $query);

    $sql = "SELECT * FROM admin";
    $result_admin = $conn->query($sql);

    if (!$result) {
    die("Query error: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/style.css">
    <meta charset="UTF-8">
    <title>Inventaris Laboratorium Informatika</title>
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
                    <li><a href="peminjaman.php" class="menu-item  <?=  $page=='peminjaman.php'?'active':''?>">Peminjaman Aktif</a></li>
                    <li><a href="../history_peminjaman.php" class="menu-item  <?=  $page=='history_peminjaman.php'?'active':''?>">History Peminjaman</a></li>
                </ul>
            </div>
            <div class="menu-group">
                <p class="menu-title">TEAMS</p>
                    <ul>
                        <?php while ($row = $result_admin->fetch_assoc()): ?>
                        <li class="menu-item"><?= $row['username']; ?></li>
                        <?php endwhile; ?>
                    </ul>
            </div>
            <div class="logout">
                <a href="../logout.php" class="menu-item logout">Logout</a>
            </div>
        </div>
        <div class="main" id="main">
            <div class="header">
                <div class="button_dash">
                    <button id="toggleSidebar">☰</button>
                    <h1>Dashboard</h1>
                </div>
                <div class="user">Selamat Datang, <br><?php echo $_SESSION["username"];?></div>
            </div>
            <div class="kategori-select">
                <form method="GET">
                    <select name="kategori" class="kategori-slct" id="kategoriSelect">
                        <option value="">Cari Kategori</option>
                        <option value="1">Alat Komputer</option>
                        <option value="2">Furniture</option>
                        <option value="3">Perangkat Audio</option>
                        <option value="4">Elektronik</option>
                        <option value="5">Pendingin</option>
                    </select>
                </form>
            </div>
                <div class="actions">
                    <button class="btn-primary"><a href="export_excel.php">Export Excel</a></button>
                    <button class="btn-primary"><a href="create.php">Tambah Data</a></button>
                </div>
            <?php if (isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show notif-fix text-center">
            Data berhasil dihapus!
            </div>
            <?php endif; ?>
            <div class="table-container">
                <h3>Inventaris Laboratorium Informatika</h3>
                <table>
                    <thead>
                        <tr class="table-header"> 
                            <th>No</th>
                            <th>Kode Inventaris</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Tipe</th>
                            <th>Spesifikasi</th>
                            <th>Jumlah</th>
                            <th>Kondisi</th>
                            <th>Lokasi</th>
                            <th>Tahun Perolehan</th>
                            <th>Keterangan</th>
                            <th>Tersedia</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                    <tbody>
                        <tr class="table-row">
                            <td><?= $no++; ?></td>
                            <td><?= $row['kode_inventaris']; ?></td>
                            <td><?= $row['nama_barang']; ?></td>
                            <td><?= $row['merk']; ?></td>
                            <td><?= $row['tipe']; ?></td>
                            <td><?= $row['spesifikasi']; ?></td>
                            <td><?= $row['jumlah']; ?></td>
                            <td><?= $row['kondisi']; ?></td>
                            <td><?= $row['lokasi']; ?></td>
                            <td><?= $row['tahun_perolehan']; ?></td>
                            <td><?= $row['keterangan']; ?></td>
                            <td><?= $row['tersedia'] == 1 ? 'Iya' : 'Tidak'; ?></td>
                            <td>
                                <a class="btn-edit"   href="edit.php?id=<?=  $row['id'];?>">Edit</a> |
                                <a class="btn-delete"  href="delete.php?id=<?=  $row['id'];?>"
                                    onclick="return confirm('Yakin mau hapus data ini?')"> 
                                    Delete
                                </a>
                            </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
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

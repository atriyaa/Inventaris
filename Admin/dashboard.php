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
    $result = mysqli_query($conn, $query)

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/style.css">
    <meta charset="UTF-8">
    <title>Inventaris Laboratorium Informatika</title>
</head>
<body class="body">
    <div class="sidebar" id="sidebar">
        <h2>INVENTARIS<br>kangen ivan LABORATORIUM<br>INFORMATIKA</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="../pinjam.php">Pinjam</a></li>
            <li><a href="peminjaman.php">Peminjaman Aktif</a></li>
            <li><a href="../history_peminjaman.php">History Peminjaman</a></li>
        </ul>
        <div class="logout">
            <a href="../logout.php">Logout</a>
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

        <div class="navigasi_kategori">
            <div class="kategori">
                <a href="dashboard.php?filter=1" class="kategori-card">Alat Komputer</a>
                <a href="dashboard.php?filter=2" class="kategori-card"">Furniture</a>
                <a href="dashboard.php?filter=3" class="kategori-card"">Perangkat Audio</a>
                <a href="dashboard.php?filter=4" class="kategori-card"">Elektronik</a>
                <a href="dashboard.php?filter=5" class="kategori-card"">Pendingin</a>
            </div>
            <div class="actions">
                <button class="btn-primary"><a href="export_excel.php">Export Excel</a></button>
                <button class="btn-primary"><a href="create.php">Tambah Data</a></button>
            </div>
        </div>
        <div class="table-container">
            <table>
                <tr> 
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
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                <tr>
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
</script>
</body>
</html>

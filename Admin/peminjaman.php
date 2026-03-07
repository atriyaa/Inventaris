<?php
    require_once __DIR__ . "/../config/database.php";

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
    <link rel="stylesheet" href="../assets/style.css">
    <meta charset="UTF-8">
    <title>Inventaris Laboratorium Informatika</title>
</head>
<body class="body">
    <div class="sidebar" id="sidebar">
        <h2>INVENTARIS<br>LABORATORIUM<br>INFORMATIKA</h2>
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
            <button id="toggleSidebar">☰</button>
            <div class="user">Selamat Datang, <br><?php echo $_SESSION["username"];?></div>
        </div>
        <h2>Peminjaman Aktif</h2>

<table border="5" cellpadding="10">
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
<td><?php echo $no++; ?></td>
<td><?php echo $row['nama_barang']; ?></td>
<td><?php echo $row['nama_peminjam']; ?></td>
<td><?php echo $row['jumlah']; ?></td>
<td><?php echo $row['keperluan']; ?></td>
<td><?php echo $row['tanggal_pinjam']; ?></td>

<td>
<a href="kembalikan.php?id=<?php echo $row['id']; ?>">
<button>Kembalikan</button>
</a>
</td>

</tr>

<?php } ?>

</table>
<a href="dashboard.php">Back To Dashboard</a>
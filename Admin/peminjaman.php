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

<h2>Peminjaman Aktif</h2>

<table border="1" cellpadding="10">
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
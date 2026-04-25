<?php
    require_once __DIR__ . "/../config/database.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=inventaris_peminjaman.xls");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Peminjam</th>
        <th>nama barang</th>
        <th>keperluan</th>
        <th>Jumlah Barang Dipinjam</th>
        <th>Tanggal Peminjaman</th>
        <th>Tanggal Pengembalian</th>
        <th>Status</th>
    </tr>

<?php
$no = 1;
$query = mysqli_query($conn, "SELECT nama_peminjam, keperluan, jml_brng_pinjam, tanggal_pinjam, tanggal_kembali, status, nama_barang FROM peminjaman INNER JOIN barang ON peminjaman.id = barang.id");

while($data = mysqli_fetch_assoc($query)){
?>

<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $data['nama_peminjam']; ?></td>
    <td><?php echo $data['nama_barang']; ?></td>
    <td><?php echo $data['keperluan']; ?></td>
    <td><?php echo $data['jml_brng_pinjam']; ?></td>
    <td><?php echo date("d M Y",strtotime($data['tanggal_pinjam'])); ?></td>
    <td><?php echo date("d M Y",strtotime($data['tanggal_kembali'])); ?></td>
    <td><?php echo $data['status']; ?></td>
</tr>

<?php } ?>

</table>
<?php
require_once "config/database.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=data_inventaris.xls");
?>

<table border="1">
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
    </tr>

<?php
$no = 1;
$query = mysqli_query($conn, "SELECT * FROM barang");

while($data = mysqli_fetch_assoc($query)){
?>

<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $data['kode_inventaris']; ?></td>
    <td><?php echo $data['nama_barang']; ?></td>
    <td><?php echo $data['merk']; ?></td>
    <td><?php echo $data['tipe']; ?></td>
    <td><?php echo $data['spesifikasi']; ?></td>
    <td><?php echo $data['jumlah']; ?></td>
    <td><?php echo $data['kondisi']; ?></td>
    <td><?php echo $data['lokasi']; ?></td>
    <td><?php echo $data['tahun_perolehan']; ?></td>
    <td><?php echo $data['keterangan']; ?></td>
    <td><?php echo $data['tersedia']; ?></td>
</tr>

<?php } ?>

</table>
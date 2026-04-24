<?php
    require_once __DIR__ . "/../config/database.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=inventaris_perawatan.xls");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Barang</th>
        <th>Tanggal Perawatan</th>
        <th>Deskripsi</th>
    </tr>

<?php
$no = 1;
$query = mysqli_query($conn, "SELECT perawatan.*,barang.nama_barang FROM perawatan JOIN barang ON perawatan.id = barang.id");

while($data = mysqli_fetch_assoc($query)){
?>

<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $data['nama_barang']; ?></td>
    <td><?php echo $data['tanggal_perbaikan']; ?></td>
    <td><?php echo $data['deskripsi']; ?></td>
</tr>

<?php } ?>

</table>
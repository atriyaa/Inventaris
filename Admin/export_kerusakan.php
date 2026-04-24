<?php
    require_once __DIR__ . "/../config/database.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=inventaris_kerusakan.xls");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Barang</th>
        <th>Tanggal Lapor</th>
        <th>Deskripsi Kerusakan</th>
        <th>Tingkat Kerusakan</th>
        <th>Status Perbaikan</th>
        <th>Biaya Perbaikan</th>
    </tr>

<?php
$no = 1;
$query = mysqli_query($conn, "SELECT nama_barang, tanggal_lapor, deskripsi_kerusakan, tingkat_kerusakan, status_perbaikan, biaya_perbaikan FROM barang INNER JOIN kerusakan ON barang.id = kerusakan.id_barang;");

while($data = mysqli_fetch_assoc($query)){
?>

<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $data['nama_barang']; ?></td>
    <td><?php echo $data['tanggal_lapor']; ?></td>
    <td><?php echo $data['deskripsi_kerusakan']; ?></td>
    <td><?php echo $data['tingkat_kerusakan']; ?></td>
    <td><?php echo $data['status_perbaikan']; ?></td>
    <td><?php echo $data['biaya_perbaikan']; ?></td>
</tr>

<?php } ?>

</table>
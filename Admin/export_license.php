<?php
    require_once __DIR__ . "/../config/database.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=inventaris_kerusakan.xls");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Software</th>
        <th>License Key</th>
        <th>Tipe License</th>
        <th>Tanggal Pembelian</th>
        <th>Tanggal Expired</th>
        <th>Jumlah User</th>
        <th>Status Aktif</th>
    </tr>

<?php
$no = 1;
$query = mysqli_query($conn, "SELECT * FROM license_software");

while($data = mysqli_fetch_assoc($query)){
?>

<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $data['nama_software']; ?></td>
    <td><?php echo $data['license_key']; ?></td>
    <td><?php echo $data['tipe_license']; ?></td>
    <td><?php echo $data['tanggal_pembelian']; ?></td>
    <td><?php echo $data['tanggal_expired']; ?></td>
    <td><?php echo $data['jumlah_user']; ?></td>
    <td><?php echo $data['status_aktif']; ?></td>
</tr>

<?php } ?>

</table>
<?php
    require_once __DIR__ . "/../config/database.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=inventaris_peminjaman.xls");
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
$query = mysqli_query($conn, "SELECT nama_peminjam, keperluan, jml_brng_pinjam, tanggal_pinjam, tanggal_kembali, status, nama_barang FROM peminjaman INNER JOIN barang ON peminjaman.id = barang.id");

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
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

    require_once __DIR__ . "/../config/database.php";

$data = mysqli_query($conn, "SELECT peminjaman.*, barang.nama_barang FROM peminjaman JOIN barang ON peminjaman.barang_id = barang.id ORDER BY tanggal_pinjam DESC");
?>

<table border="1">
<tr>
    <th>Nama</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)) { ?>
<tr>
    <td><?= $row['nama_peminjam']; ?></td>
    <td><?= $row['nama_barang']; ?></td>
    <td><?= $row['jumlah']; ?></td>
    <td><?= $row['status']; ?></td>
    <td>
        <?php if ($row['status'] == 'dipinjam') { ?>
            <a href="kembalikan.php?id=<?= $row['id']; ?>">Kembalikan</a>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</table>
<?php
require_once __DIR__ . "/../config/database.php";

// Set nama file otomatis dengan tanggal hari ini
$filename = "Data_Master_Barang_" . date('Y-m-d') . ".xls";

// Header untuk memaksa browser mendownload sebagai file Excel
header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Query JOIN antara tabel barang dan kategori
$query = "SELECT b.*, k.nama_kategori 
          FROM barang b 
          LEFT JOIN kategori k ON b.id_kategori = k.id_kategori 
          ORDER BY b.nama_barang ASC";

$sql = mysqli_query($conn, $query);
?>

<meta charset="utf-8">
<table border="1">
    <thead>
        <tr style="background-color: #3c8dbc; color: #ffffff; font-weight: bold;">
            <th>No</th>
            <th>Kategori</th>
            <th>Nama Barang</th>
            <th>Merk</th>
            <th>Tipe</th>
            <th>Spesifikasi</th>
            <th>Ketersediaan</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $no = 1;
    if ($sql && mysqli_num_rows($sql) > 0) {
        while ($data = mysqli_fetch_assoc($sql)) {
    ?>
        <tr>
            <td style="text-align: center;"><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($data['nama_kategori'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($data['nama_barang']); ?></td>
            <td><?php echo htmlspecialchars($data['merk'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($data['tipe'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($data['spesifikasi'] ?? '-'); ?></td>
            <td style="text-align: center;"><?php echo htmlspecialchars($data['tersedia']); ?></td>
        </tr>
    <?php 
        }
    } else {
    ?>
        <tr>
            <td colspan="7" style="text-align: center;">Data barang masih kosong.</td>
        </tr>
    <?php } ?>
    </tbody>
</table>
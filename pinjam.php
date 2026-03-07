<?php
require_once "config/database.php";
session_start();

if (isset($_POST["pinjam"])) {
    $nama_peminjam = $_POST['nama_peminjam'];
    $keperluan = $_POST['keperluan'];
    $jumlah = $_POST['jumlah'];
    $barang_id = $_POST['barang_id'];

    $sql =  "INSERT INTO peminjaman (nama_peminjam, keperluan, jumlah, barang_id) VALUES ('$nama_peminjam', '$keperluan', '$jumlah', '$barang_id')";
    if (mysqli_query($conn, $sql)) {
        $create_message = "Data berhasil ditambahkan";
        $message_type = "success";
    } else {
        $create_message = "Data gagal ditambahkan";
        $message_type = "error";
    }
}
$query = mysqli_query($conn, "SELECT barang.*, IFNULL(SUM(peminjaman.jumlah),0) AS sedang_dipinjam FROM barang
LEFT JOIN peminjaman ON barang.id = peminjaman.barang_id AND peminjaman.status='dipinjam' GROUP BY barang.id
");
$brg = mysqli_query($conn, "SELECT * FROM barang");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <h2>INVENTARIS<br>LABORATORIUM<br>INFORMATIKA</h2>
        <ul>
            <li><a href="Admin/dashboard.php">Dashboard</a></li>
            <li><a href="../pinjam.php">Pinjam</a></li>
            <li><a href="Admin/peminjaman.php">Peminjaman Aktif</a></li>
            <li><a href="../history_peminjaman.php">History Peminjaman</a></li>
        </ul>
        <div class="logout">
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    <div class="main" id="main">
        <div class="header">
            <div class="button_dash">
                <button id="toggleSidebar">☰</button>
                <h1>Pinjam Barang</h1>
            </div>
            <div class="user">Selamat Datang, <br><?php echo $_SESSION["username"];?></div>
        </div>
    <table class="table-peminjaman" border="1">
        <tr>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Sedang Dipinjam</th>
            <th>Stok</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($query)) { ?>
            <?php
            $stok_tersedia = $row['jumlah'] - $row['sedang_dipinjam'];
            ?>
            <tr>
                <td><?php echo $row['nama_barang']; ?></td>
                <td><?php echo $row['jumlah']; ?></td>
                <td><?php echo $row['sedang_dipinjam']; ?></td>
                <td><?php echo $stok_tersedia; ?></td>
            </tr>
        <?php } ?>

    </table>
    <form method="POST">
        <label for="pilih barang">Pilih Barang</label><br>
        <select name="barang_id" required>
            <option value="">-- Pilih Barang --</option>
            <?php while($row = mysqli_fetch_assoc($brg)) { ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['nama_barang']; ?>
                </option>
            <?php } ?>
        </select>

        <label for="jumlah">Jumlah</label><br>
        <input type="number" name="jumlah" required>
        <br><br>

        <label for="nama_peminjam">Nama Peminjam</label><br>
        <input type="text" name="nama_peminjam" required>
        <br><br>

        <label for="keperluan">Keperluan</label><br>
        <input type="text" name="keperluan" required>
        <br><br>

        <button type="submit" name="pinjam">Pinjam</button><br><br>
        <a href="Admin/dashboard.php" class="adalah">Back to Dashboard</a>
    </form>
<script>
    const toggle = document.getElementById("toggleSidebar");
    const sidebar = document.querySelector(".sidebar");
    const main = document.querySelector(".main")

    toggle.onclick = function() {
    sidebar.classList.toggle("close");
    main.classList.toggle("full");
    }
</script>
</body>
</html>  
<?php
require_once "config/database.php";
session_start();
$create_message = '';

if (isset($_POST["pinjam"])) {
    $nama_peminjam = $_POST['nama_peminjam'];
    $keperluan = $_POST['keperluan'];
    $jumlah = $_POST['jumlah'];
    $barang_id = $_POST['barang_id'];

    $sql =  "INSERT INTO peminjaman (nama_peminjam, keperluan, jumlah, barang_id) VALUES ('$nama_peminjam', '$keperluan', '$jumlah', '$barang_id')";
    if (mysqli_query($conn, $sql)) {
        header("Location: pinjam.php?pinjam=success");
        exit;
    } else {
        $create_message = "Data gagal ditambahkan";
        $message_type = "error";
    }
}
$query = mysqli_query($conn, "SELECT barang.*, IFNULL(SUM(peminjaman.jumlah),0) AS sedang_dipinjam FROM barang
LEFT JOIN peminjaman ON barang.id = peminjaman.barang_id AND peminjaman.status='dipinjam' GROUP BY barang.id
");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pinjam</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/style.css">
        <style>
        :root {
            --primary-blue: #2c3e50; /* Biru Tua Akademis */
            --secondary-blue: #3498db; /* Biru Terang */
            --light-blue: #ecf0f1;
            --sidebar-dark: #222d32;
            --white: #ffffff;
            --gray-text: #7f8c8d;
            --border-color: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f4f6f9;
        }

        /* --- SIDEBAR --- */
        aside {
            width: 250px;
            background-color: var(--sidebar-dark);
            color: #b8c7ce;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1001; /* Di atas segalanya */
            overflow-y: auto; /* Agar menu bisa di-scroll jika kepanjangan */
            transition: width 0.3s ease;
        }

        .user-panel {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #374850;
        }

        .user-panel img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 10px;
            margin-left: 10px;
            border: 2px solid var(--secondary-blue);
        }

        /* Class tambahan untuk efek toggle */
        #toggle-btn {
            font-size: 20px;
            transition: transform 0.3s;
            cursor: pointer;
        }

        #toggle-btn:hover {
            transform: scale(1.1); /* Efek membesar sedikit saat disentuh mouse */
        }

        aside.collapsed {
            width: 100px; /* Lebar sidebar saat tertutup */
        }

        aside.collapsed + main {
            margin-left: 70px;
        }

        aside.collapsed + main header {
            left: 70px;
        }

        aside.collapsed .user-panel div, 
        aside.collapsed .sidebar-menu li span,
        aside.collapsed .menu-header {
            display: none; /* Sembunyikan teks saat tertutup */
        }

        aside.collapsed .sidebar-menu li {
            text-align: center;
            padding: 15px 0;
        }

        aside.collapsed .sidebar-menu li i {
            margin: 0;
            font-size: 18px;
        }

        /* Transisi halus */
        aside, main {
            transition: all 0.5s ease;
        }

        .sidebar-menu {
            list-style: none;
            padding: 10px 0;
        }

        .sidebar-menu li {
            padding: 12px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
        }

        .sidebar-menu a{
            color: #f4f6f9;
            text-decoration: none;
        }

        .sidebar-menu li:hover, .sidebar-menu li.active {
            background-color: #1e282c;
            color: white;
            border-left: 3px solid var(--secondary-blue);
        }

        .menu-header {
            font-size: 12px;
            padding: 15px 20px 5px;
            color: #4b646f;
            text-transform: uppercase;
        }
        main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            margin-left: 250px; 
            margin-top: 50px; /* Sesuai tinggi header */
            transition: margin-left 0.3s ease;
        }

        header {
            height: 50px;
            background-color: var(--secondary-blue);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            color: white;
            position: fixed;
            top: 0;
            right: 0;
            left: 250px; /* Jaraknya mengikuti lebar sidebar */
            z-index: 1000;
            transition: left 0.3s ease;
        }
        header a{
            color: white;
            text-decoration: none;
        }
        .breadcrumb {
            background: white;
            padding: 10px 20px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
        }
        .content-wrapper {
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 4px;
            border-top: 3px solid var(--secondary-blue);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px;
        }
                .page-header h3 {
            color: #2c3e50;
            font-size: 18px; /* Kecilkan sedikit */
            margin: 0 0 5px;
        }
        .page-header p {
            font-size: 12px;
            color: #bdc3c7;
            margin: 0 0 20px;
        }

        .btn-simpan, .btn-batal {
            min-width: 150px; /* Biar tombol punya lebar yang sama dan proporsional */
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Tombol */
        .form-actions {
            display: flex;
            justify-content: center; 
            align-items: center;
            gap: 20px; /* Jarak antar tombol */
            margin-top: 20px;
            gap: 15px;
        }

        .btn-simpan {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-simpan:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-batal {
            background: #f8f9fa;
            color: #636e72;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 14px;
            border: 1px solid #dcdfe6;
        }

        .btn-batal:hover {
            background: #eee;
        }
                a {
            text-decoration: none;
        }
        .content-wrapper {
            padding: 50px;
        }
        /* Container Utama */
        .page-header {
            margin-bottom: 30px;
        }
        .card-form {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 800px; 
            margin: 20px auto;
            padding: 25px; 
            border-top: 4px solid #3498db;
            border: 1px solid rgba(220, 223, 230, 0.7);
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 5px; 
            font-size: 13px; 
        }

        .input-group input, 
        .input-group select, 
        .input-group textarea {
            width: 100%;
            padding: 10px 12px; 
            border: 1px solid #dcdfe6;
            border-radius: 6px; 
            font-size: 13px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            background: #fdfdfd;
        }

        .input-group input:focus, .input-group select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.15);
        }
                .header-section {
            max-width: 900px;
            margin: 0 auto 20px auto; /* Margin auto agar sejajar dengan card bawah */
            text-align: left;
        }

        .header-section h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }

        .header-section p {
            margin: 5px 0 15px;
            color: #7f8c8d;
            font-size: 14px;
        }
        </style>
    </head>
<body class="body">
    <?php $page = basename($_SERVER['PHP_SELF']); ?>
    <?php include "../include/sidebar.php" ?>
    <main>
        <header>
            <i class="fa fa-bars" id="toggle-btn"></i>
            <div style="font-size: 14px;">
                Ahmad Jhony - administrator &nbsp; <i class="fa fa-sign-out"><a href="logout.php" style="color: #000000;"> LOGOUT</a></i> 
            </div>
        </header>
        <div class="breadcrumb">
            <h2 style="font-size: 18px; color: #333;">Barang <small style="color: #999; font-weight: 300;">Data Barang</small></h2>
            <div><i class="fa fa-home"></i> Home > Dashboard</div>
        </div>
        <div class="content-wrapper">
            <div class="card">
                <?php if (isset($_GET['pinjam']) && $_GET['pinjam'] == 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show notif-fix text-center">
                    Peminjaman berhasil!
                </div>
                <?php endif; ?>
                <div class="info-barang hidden-info" id="infoBarangBox">
                    <h5>Info Barang</h5>
                    <div class="info-card">
                        <p><b>Nama Barang :</b><span id="namaBarang">-</span></p>
                        <p><b>Stok :</b><span id="stokBarang">-</span></p>
                        <p><b>Sedang Dipinjam :</b><span id="dipinjamBarang">-</span></p>
                    </div>
                </div>
                <form autocomplete="off"  method="POST">
                    <div class="input-group">
                        <label for="pilih barang">Pilih Barang</label>
                        <select  id="barangSelect" name="barang_id" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php while($row = mysqli_fetch_assoc($query)) { 
                                $stok_tersedia = $row['jumlah'] - $row['sedang_dipinjam']; 
                            ?>
                            <option value="<?php echo $row['id']; ?>"
                            data-nama="<?php echo $row['nama_barang']; ?>"
                            data-stok="<?php echo $stok_tersedia; ?>"
                            data-dipinjam="<?php echo $row['sedang_dipinjam']; ?>">
                            <?php echo $row['nama_barang']; ?>
                        </option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" required>
                    </div>
                    <div class="input-group">
                        <label for="nama_peminjam">Nama Peminjam</label>
                        <input type="text" name="nama_peminjam" required>
                    </div>
                    <div class="input-group">
                        <label for="keperluan">Keperluan</label>
                        <input type="text" name="keperluan" required>
                    </div>
                    <div class="form-actions">
                        <a href="Admin/dashboard.php" class="btn-batal">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button name="pinjam"  type="submit" class="btn-simpan">
                            <i class="fa fa-save"></i> Simpan Data
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        const toggleBtn = document.getElementById('toggle-btn');
        const sidebar = document.querySelector('aside');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
        document.addEventListener("DOMContentLoaded", function() { 
            const barangSelect = document.getElementById("barangSelect");
            const infoBox = document.getElementById("infoBarangBox");

            const namaBarang = document.getElementById("namaBarang");
            const stokBarang = document.getElementById("stokBarang");
            const dipinjamBarang = document.getElementById("dipinjamBarang");

            barangSelect.addEventListener("change", function() {
                console.log(barangSelect);
                const selected = this.options[this.selectedIndex];

                const nama = selected.getAttribute("data-nama");
                const stok = selected.getAttribute("data-stok");
                const dipinjam = selected.getAttribute("data-dipinjam");

                if(nama) {
                    infoBox.classList.remove("hidden-info");

                    namaBarang.textContent = nama;
                    stokBarang.textContent = stok;
                    dipinjamBarang.textContent = dipinjam;
                } else {
                    infoBox.classList.add("hidden-info");
                }
            });
        });
        setTimeout(() => {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500); // hilang total
            }
        }, 5000); // 3000ms = 3 detik
    </script>
</body>
</html>  
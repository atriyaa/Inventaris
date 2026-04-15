<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();
    if (!isset($_SESSION['admin'])) {
        header("Location: ../login.php");
        exit;
    }
    $lab = $_GET['lab'] ?? null;
    $filter = $_GET['filter'] ?? 'all';
    $filter = mysqli_real_escape_string($conn, $filter);
    $where = [];

    if ($filter != 'all') {
        $where[] = "barang.kategori_id = '$filter'";
    }

    if ($lab == 'lab_mm') {
        $where[] = "barang.lokasi = 'LAB MM'";
    } elseif ($lab == 'lab_jarkom') {
        $where[] = "barang.lokasi = 'LAB Jarkom'";
    }

    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }

    $limit = 15;
    $halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
    if ($halaman_aktif <= 0) $halaman_aktif = 1;
    $offset = ($halaman_aktif - 1) * $limit;

    // Hitung total data untuk tahu jumlah halaman
    $query_total = "SELECT COUNT(*) AS total FROM barang JOIN kategori ON barang.kategori_id = kategori.id $where_sql";
    $result_total = mysqli_query($conn, $query_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_data = $row_total['total'];
    $total_halaman = ceil($total_data / $limit);

    $query = "
        SELECT barang.*, kategori.nama_kategori
        FROM barang
        JOIN kategori ON barang.kategori_id = kategori.id
        $where_sql 
        ORDER BY barang.id DESC
        LIMIT $limit OFFSET $offset
    ";
    $result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Laboratorium Informatika</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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

        /* --- MAIN CONTENT --- */
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

        /* --- DATA TABLE SECTION --- */
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

        .alert-container {
            margin-bottom: 20px;
            animation: fadeInDown 0.5s ease; /* Efek muncul dari atas */
        }

        .alert {
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 5px solid;
        }

        /* Warna Hijau untuk Hapus (Sukses) */
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border-color: #d6e9c6;
            border-left-color: #2ecc71;
        }

        /* Warna Biru untuk Edit (Info) */
        .alert-info {
            background-color: #d9edf7;
            color: #31708f;
            border-color: #bce8f1;
            border-left-color: #3498db;
        }

        /* Animasi sederhana */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header a{
            text-decoration: none;
            color: #ffffff;
        }

        .btn-tambah {
            background-color: #5bc0de;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }

        .kategori-box {
            display: flex;
            align-items: center;
            gap: 10px;
            float: right;
            margin-bottom: 10px;
        }

        .kategori-box input {
            border: 1px solid var(--border-color);
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        table th, table td {
            border: 1px solid var(--border-color);
            padding: 12px;
            text-align: left;
        }

        table th {
            background-color: #f9f9f9;
            font-weight: 600;
        }

        .btn-action {
            padding: 5px 8px;
            border: none;
            border-radius: 3px;
            color: white;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-edit { background-color: #f0ad4e; }
        .btn-delete { background-color: #d9534f; }

        .table-footer {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--gray-text);
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
        }

        .pagination li .page-link {
            padding: 8px 16px;
            text-decoration: none;
            color: #3498db; /* Biru akademik */
            border: 1px solid #dee2e6;
            display: block; /* Penting: agar seluruh area kotak bisa diklik */
        }

        .pagination li.active .page-link {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }

        .pagination li.disabled .page-link {
            color: #ccc;
            pointer-events: none; /* Mematikan klik jika di halaman 1 */
            background-color: #f8f9fa;
        }

        .pagination li:first-child .page-link {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .pagination li:last-child .page-link {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
    </style>
</head>
<body>
    <?php $page = basename($_SERVER['PHP_SELF']); ?>
    <aside>
        <div class="user-panel">
            <img src="image/rooney.jpg" alt="User">
            <div>
                <p style="color: white; font-weight: 600;">Ahmad Jhony</p>
                <small style="color: #2ecc71;"><i class="fa fa-circle"></i> Online</small>
            </div>
        </div>
        
        <ul class="sidebar-menu">
            <li class="menu-header">MAIN NAVIGATION</li>
            <li class="active"><a href="dashboard.php"><span> &nbsp; DASHBOARD</span></a></li>
            <li class=""><a href="../pinjam.php"><span> &nbsp; PINJAM BARANG</span></a></li>
            <li class=""><a href="peminjaman.php"><span> &nbsp; PEMINJAMAN AKTIF</span></a></li>
            <li class=""><a href="history_peminjaman.php"><span> &nbsp; HISTORY PEMINJAMAN</span></a></li>
        </ul>
    </aside>

    <main>
        <header>
            <i class="fa fa-bars" id="toggle-btn"></i>
            <div style="font-size: 14px;">
                Ahmad Jhony - administrator &nbsp; <i class="fa fa-sign-out"><a href="logout.php" style="color: #000000;"> LOGOUT</a></i> 
            </div>
        </header>

        <div class="breadcrumb">
            <h2 style="font-size: 18px; color: #333;">Barang <small style="color: #999; font-weight: 300;">Data Barang</small></h2>
            <div><i class="fa fa-home"></i> <a href="logout.php">Home</a> <a href="dashboard.php"> > Dashboard</a></div>
        </div>

        <div class="content-wrapper">
            <div class="card">
                <?php if (isset($_GET['pesan'])): ?>
                    <div class="alert-container">
                        <?php if ($_GET['pesan'] == 'hapus'): ?>
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> Data barang berhasil <strong>dihapus</strong>!
                            </div>
                        <?php elseif ($_GET['pesan'] == 'edit'): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Data barang berhasil <strong>diperbarui</strong>!
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="card-header">
                    <h3 style="font-size: 16px;">Barang</h3>
                    <button class="btn-tambah"> <a href="create.php">+ Tambah Barang</a></button>
                </div>
                <div class="card-header">
                    <h3 style="font-size: 16px;"></h3>
                    <button class="btn-tambah"> <a href="export_excel.php"> > Export Excel</a></button>
                </div>

                <div class="kategori-box">
                    <form method="GET">
                        <select name="filter" id="kategoriSelect" onchange="this.form.submit()">
                            <option value="">Cari Kategori</option>
                            <option value="1">Alat Komputer</option>
                            <option value="2">Furniture</option>
                            <option value="3">Perangkat Audio</option>
                            <option value="4">Elektronik</option>
                            <option value="5">Pendingin</option>
                        </select>
                    </form>
                </div>
                <table>
                    <thead>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                        <?php
                        $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                    <tbody>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['kode_inventaris']; ?></td>
                            <td><?= $row['nama_barang']; ?></td>
                            <td><?= $row['merk']; ?></td>
                            <td><?= $row['tipe']; ?></td>
                            <td><?= $row['spesifikasi']; ?></td>
                            <td><?= $row['jumlah']; ?></td>
                            <td><?= $row['kondisi']; ?></td>
                            <td><?= $row['lokasi']; ?></td>
                            <td><?= $row['tahun_perolehan']; ?></td>
                            <td><?= $row['keterangan']; ?></td>
                            <td><?= $row['tersedia'] == 1 ? 'Iya' : 'Tidak'; ?></td>
                            <td>
                                <a class="btn-action btn-edit" href="edit.php?id=<?= $row['id']; ?>"><i class="fa fa-cog"></i></a>
                                <a class="btn-action btn-delete" href=delete.php?id=<?= $row['id']; ?>"><i class="fa fa-trash"></i></a>
                            </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>

                <div class="table-footer">
                    <p>Showing <?= ($offset + 1); ?> to <?= min($offset + $limit, $total_data); ?> of <?= $total_data; ?> entries</p>
                    <ul class="pagination">
                        <?php if($halaman_aktif > 1): ?>
                            <li><a href="?halaman=<?= $halaman_aktif - 1; ?>&lab=<?= $lab; ?>&filter=<?= $filter; ?>">Previous</a></li>
                        <?php else: ?>
                            <li class="disabled">Previous</li>
                        <?php endif; ?>

                        <li class="active"><?= $halaman_aktif; ?></li>

                        <?php if($halaman_aktif < $total_halaman): ?>
                            <li><a href="?halaman=<?= $halaman_aktif + 1; ?>&lab=<?= $lab; ?>&filter=<?= $filter; ?>">Next</a></li>
                        <?php else: ?>
                            <li class="disabled">Next</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
<script>
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.querySelector('aside');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

    const alertBox = document.querySelector('.alert-container');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000); // 3000ms = 3 detik
    }
</script>
</body>
</html>
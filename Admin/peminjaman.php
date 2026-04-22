<?php
    require_once __DIR__ . "/../config/database.php";
    session_start();

    $limit = 15;
    $halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
    if ($halaman_aktif <= 0) $halaman_aktif = 1;
    $offset = ($halaman_aktif - 1) * $limit;

    // 1. Definisikan variabel awal agar tidak "Undefined"
    $where_sql = ''; 
    $where = [];

    // 2. Logika filter (ambil dari kode sebelumnya)
    $lab = $_GET['lab'] ?? null;
    $filter = $_GET['filter'] ?? 'all';

    if ($filter != 'all') {
    $where[] = "barang.kategori_id = '$filter'";
    }

    if ($lab == 'lab_mm') {
    $where[] = "barang.lokasi = 'LAB MM'";
    } elseif ($lab == 'lab_jarkom') {
    $where[] = "barang.lokasi = 'LAB Jarkom'";
    }

    // 3. Gabungkan array menjadi string WHERE
    if (!empty($where)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where);
    }

    // Hitung total data untuk tahu jumlah halaman
    $query_total = "SELECT COUNT(*) AS total 
                    FROM peminjaman 
                    JOIN barang ON peminjaman.barang_id = barang.id 
                    $where_sql";
    $result_total = mysqli_query($conn, $query_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_data = $row_total['total'];
    $total_halaman = ceil($total_data / $limit);
$where_sql = "WHERE peminjaman.status = 'dipinjam'";
$query = mysqli_query($conn,"
    SELECT peminjaman.*, barang.nama_barang 
    FROM peminjaman
    JOIN barang ON peminjaman.barang_id = barang.id
    $where_sql 
    ORDER BY peminjaman.id DESC
    LIMIT $limit OFFSET $offset
");

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
            width: 70px; /* Lebar sidebar saat tertutup */
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
            margin-left: 250px; /* Sesuaikan dengan lebar aside */
            padding-top: 50px;  /* Sesuaikan dengan tinggi header */
            width: calc(100% - 250px); /* Memastikan lebar konten sisa layar */
            transition: all 0.3s ease;
        }

        header {
            position: fixed;
            top: 0;
            right: 0;
            left: 250px; /* Header mulai setelah sidebar */
            z-index: 1000;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            transition: all 0.3s ease;
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

        tr, td, a{
            text-decoration: none;
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

        .btn-kembali {
            background-color: #f3f4f7;
            border: 1px solid #dcdfe6;
            padding: 5px 10px;
            border-radius: 4px;
            color: #606266;
            cursor: pointer;
            font-size: 12px;
            transition: 0.2s;
            align-items: center;
        }


        .btn-kembali:hover {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
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
            <li class=""><a href="dashboard.php"><span> &nbsp; DASHBOARD</span></a></li>
            <li class=""><a href="../pinjam.php"><span> &nbsp; PINJAM BARANG</span></a></li>
            <li class="active"><a href="peminjaman.php"><span> &nbsp; PEMINJAMAN AKTIF</span></a></li>
            <li class=""><a href="history_peminjaman.php"><span> &nbsp; HISTORY PEMINJAMAN</span></a></li>
        </ul>
    </aside>

    <main>
        <header>
            <i class="fa fa-bars" id="toggle-btn"></i>
            <div style="font-size: 14px;">
                Ahmad Jhony - administrator &nbsp; <i class="fa fa-sign-out"><a href="logout.php"> LOGOUT</a></i> 
            </div>
        </header>

        <div class="breadcrumb">
            <h2 style="font-size: 18px; color: #333;">Peminjaman <small style="color: #999; font-weight: 300;">Data Peminjaman</small></h2>
            <div><i class="fa fa-home"></i> <a href="logout.php">Home</a> > <a href="dashboard.php">Dashboard</a></div>
        </div>

        <div class="content-wrapper">
            <div class="card">
                <?php if (isset($_GET['return'])): ?>
                    <div class="alert-container">
                        <?php if ($_GET['return'] == 'success') ?>
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> Data barang berhasil <strong>dikembalikan</strong>!
                            </div>
                    </div>
                <?php endif; ?>
                    <div class="card-header">
                    <h3 style="font-size: 16px;">Barang</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Nama Peminjam</th>
                                <th>Jumlah</th>
                                <th>Keperluan</th>
                                <th>Tanggal Pinjam</th>
                                <th>Aksi</th>
                        </tr>
                    </thead>
                        <?php
                        $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                    <tbody>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nama_barang']; ?></td>
                            <td><?= $row['nama_peminjam']; ?></td>
                            <td><?= $row['jumlah']; ?></td>
                            <td><?= $row['keperluan']; ?></td>
                            <td><?= $row['tanggal_pinjam']; ?></td> 
                            <td>
                                <a class="btn-kembali"  href="kembalikan.php?id=<?= $row['id']; ?>">
                                    Kembalikan
                                </a>
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
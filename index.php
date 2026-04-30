<?php
    require_once "config/database.php";
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
    $query_total = "SELECT COUNT(*) AS total FROM barang JOIN kategori ON barang.id_kategori = kategori.id_kategori $where_sql";
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
<html lang="en">
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="../assets/style.css"
        <meta charset="UTF-8">
        <title>Inventaris Laboratorium Informatika</title>
        <style>
            /* Reset & Base */
            body {
                background-color: #f4f7fa;
                background-image: radial-gradient(#dce4f0 1.5px, transparent 1.5px);
                background-size: 25px 25px; /* Pattern titik halus agar tidak monoton */
                font-family: 'Inter', 'Segoe UI', sans-serif;
                margin: 0;
                padding: 50px 20px;
                color: #333;
            }

            .main-container {
                max-width: 1100px;
                margin: 0 auto;
            }

            /* Header Section */
            .header-view {
                text-align: center;
                margin-bottom: 40px;
            }

            .header-view h1 {
                font-size: 32px;
                color: #2c3e50;
                margin: 0;
                font-weight: 800;
            }

            .header-view p {
                color: #7f8c8d;
                margin-top: 5px;
            }

            /* Filter */
            .filter-wrapper {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 15px;
            }

            .select-filter {
                padding: 10px 15px;
                border-radius: 8px;
                border: 1px solid #dcdfe6;
                background: white;
                outline: none;
                color: #606266;
                cursor: pointer;
            }

            /* Table Card */
            .table-card {
                background: white;
                padding: 20px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                overflow-x: auto; /* Biar aman kalau dibuka di HP */
            }

            .styled-table {
                width: 100%;
                border-collapse: collapse;
            }

            .styled-table th {
                background-color: #ffffff;
                color: #909399;
                text-transform: uppercase;
                font-size: 12px;
                letter-spacing: 1px;
                padding: 15px;
                text-align: left;
                border-bottom: 2px solid #3498db; /* Garis biru identitas lab */
            }

            .styled-table td {
                padding: 15px;
                border-bottom: 1px solid #f2f2f2;
                font-size: 14px;
            }

            .kode-text {
                font-weight: 700;
                color: #2c3e50;
            }

            /* Badge Status */
            .badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 700;
            }

            .badge-ada {
                background: #e1f7e3;
                color: #27ae60;
            }

            .badge-tidak {
                background: #fdeaea;
                color: #e74c3c;
            }

            /* --- THE SECRET LOGIN BUTTON --- */
            .secret-gate {
                position: fixed;
                bottom: 15px;
                right: 15px;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                
                /* Disembunyikan: warnanya sama dengan background body */
                color: #f4f7fa; 
                background: transparent;
                border-radius: 50%;
                transition: all 0.4s ease;
                font-size: 14px;
                opacity: 0.5;
            }

            /* Muncul saat kamu arahkan kursor (Hanya admin yang tahu) */
            .secret-gate:hover {
                color: #3498db;
                background: white;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                opacity: 1;
            }
            
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
<div class="main-container">
    <div class="header-view">
        <h1>Daftar Inventaris Lab</h1>
        <p>Informasi ketersediaan barang secara real-time</p>
    </div>
    <div class="filter-wrapper">
        <form method="GET">
            <select name="filter" class="select-filter" id="kategoriSelect" onchange="this.form.submit()">
                <option value="">Cari Kategori</option>
                <option value="1">Alat Komputer</option>
                <option value="2">Furniture</option>
                <option value="3">Perangkat</option>
                <option value="4">Elektronik</option>
                <option value="5">Pendingin</option>
            </select>
        </form>
    </div>
    <div class="table-card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Spesifikasi</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Lokasi</th>
                    <th>Tersedia</th>
                </tr>
            </thead>
            <?php
                $no = $offset + 1;
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tbody>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><span class="kode-text"><?= $row['kode_inventaris']; ?></span></td>
                    <td><?= $row['nama_barang']; ?></td>
                    <td><?= $row['merk']; ?></td>
                    <td><?= $row['spesifikasi']; ?></td>
                    <td><?= $row['jumlah']; ?></td>
                    <td><?= $row['kondisi']; ?></td>
                    <td><?= $row['lokasi']; ?></td>
                    <td><?= $row['tersedia'] == 1 ? 'Iya' : 'Tidak'; ?></td>
                </tr>
                <?php } ?>
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
<a href="login.php" class="secret-gate" title="Admin Access>
    <i class="fa fa-lock"></i>
</a>
</body>
</html>
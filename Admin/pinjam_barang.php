<?php
require_once __DIR__ . "/../config/database.php";
session_start();

// --- LOGIKA SIMPAN DATA ---
if (isset($_POST["simpan_peminjaman"])) {
    $nama_peminjam = mysqli_real_escape_with_string($conn, $_POST['nama_peminjam']);
    $tgl_pinjam    = $_POST['tgl_pinjam'];
    $tgl_kembali   = $_POST['tgl_kembali'];
    $catatan       = mysqli_real_escape_with_string($conn, $_POST['catatan']);
    $items         = $_POST['selected_items']; // Ambil array id_detail dari input hidden

    if (!empty($items)) {
        // 1. Insert ke tabel master: peminjaman
        $sql_master = "INSERT INTO peminjaman (nama_peminjam, tgl_pinjam, tgl_kembali, catatan, status) 
                       VALUES ('$nama_peminjam', '$tgl_pinjam', '$tgl_kembali', '$catatan', 'dipinjam')";
        
        if (mysqli_query($conn, $sql_master)) {
            $id_peminjaman = mysqli_insert_id($conn); // Ambil ID yang baru saja digenerate

            // 2. Insert ke tabel detail: peminjaman_detail
            foreach ($items as $id_detail_barang) {
                $sql_detail = "INSERT INTO peminjaman_detail (id_peminjaman, id_detail) 
                               VALUES ('$id_peminjaman', '$id_detail_barang')";
                mysqli_query($conn, $sql_detail);
            }

            header("Location: pinjam.php?status=success");
            exit;
        }
    }
}

// --- QUERY TAMPIL BARANG & HITUNG STOK REALTIME ---
// Sesuaikan 'barang_detail' dengan nama tabel master barang Anda
$query = mysqli_query($conn, "
    SELECT 
        b.*, 
        (SELECT COUNT(*) 
         FROM peminjaman_detail pd
         JOIN peminjaman p ON p.id_peminjaman = pd.id_peminjaman 
         WHERE pd.id_detail = b.id_detail 
         AND p.status_pinjam = 'proses'
        ) AS sedang_dipinjam 
    FROM barang_detail b
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Tambah Peminjaman</title>
    <style>
        .custom-shadow { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-gray-100 font-sans p-4 md:p-8">

    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">2. HALAMAN TAMBAH PEMINJAMAN</h2>
            <nav class="text-sm text-blue-500 mt-1">
                <a href="#" class="hover:underline">Peminjaman</a> &nbsp;/&nbsp; 
                <span class="text-gray-400">Tambah Peminjaman</span>
            </nav>
        </div>

        <form method="POST" action="">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <div class="lg:col-span-4 space-y-4">
                    <div class="bg-white p-6 rounded-lg border custom-shadow">
                        <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Data Peminjam</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase">Nama Peminjam <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_peminjam" required class="w-full mt-1 p-2 border rounded focus:border-blue-400 outline-none text-sm" placeholder="Masukkan nama peminjam">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase">Tanggal Pinjam <span class="text-red-500">*</span></label>
                                <input type="date" name="tgl_pinjam" value="<?= date('Y-m-d') ?>" required class="w-full mt-1 p-2 border rounded text-sm outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase">Tanggal Kembali (Rencana) <span class="text-red-500">*</span></label>
                                <input type="date" name="tgl_kembali" required class="w-full mt-1 p-2 border rounded text-sm outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase">Catatan</label>
                                <textarea name="catatan" rows="3" class="w-full mt-1 p-2 border rounded text-sm outline-none" placeholder="Catatan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6">
                    
                    <div class="bg-white p-6 rounded-lg border custom-shadow">
                        <h3 class="font-bold text-gray-700 mb-4">Pilih Barang</h3>
                        
                        <div class="relative mb-4">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-300"></i>
                            </span>
                            <input type="text" id="searchInput" onkeyup="searchTable()" class="w-full pl-10 p-2 border rounded text-sm" placeholder="Cari barang...">
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border" id="tableBarang">
                                <thead class="bg-gray-50 text-gray-600 border-b">
                                    <tr>
                                        <th class="p-3">Pilih</th>
                                        <th class="p-3">Nama Barang</th>
                                        <th class="p-3">Kategori</th>
                                        <th class="p-3 text-center">Stok Tersedia</th>
                                        <th class="p-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($query)) { 
                                        $stok_tersedia = $row['stok'] - $row['sedang_dipinjam'];
                                    ?>
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="p-3 text-center">
                                            <input type="checkbox" id="check-<?= $row['id_detail'] ?>" 
                                                   onclick="toggleItem(<?= $row['id_detail'] ?>, '<?= $row['nama_barang'] ?>', '<?= $row['kategori'] ?>')"
                                                   <?= ($stok_tersedia <= 0) ? 'disabled' : '' ?>>
                                        </td>
                                        <td class="p-3 font-medium"><?= $row['nama_barang'] ?></td>
                                        <td class="p-3 text-gray-500"><?= $row['kategori'] ?></td>
                                        <td class="p-3 text-center font-bold <?= ($stok_tersedia <= 0) ? 'text-red-500' : 'text-gray-700' ?>">
                                            <?= $stok_tersedia ?>
                                        </td>
                                        <td class="p-3 text-center">
                                            <button type="button" 
                                                    onclick="addItem(<?= $row['id_detail'] ?>, '<?= $row['nama_barang'] ?>', '<?= $row['kategori'] ?>')" 
                                                    class="bg-blue-600 text-white w-8 h-8 rounded hover:bg-blue-700 transition"
                                                    <?= ($stok_tersedia <= 0) ? 'disabled style="opacity:0.5"' : '' ?>>+</button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg border custom-shadow">
                        <h3 class="font-bold text-gray-700 mb-4">Barang yang Dipilih (<span id="countItems">0</span>)</h3>
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 border-b">
                                <tr class="text-gray-600">
                                    <th class="p-3">No</th>
                                    <th class="p-3">Nama Barang</th>
                                    <th class="p-3">Kategori</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="selectedTableBody" class="divide-y">
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-gray-400 italic">Belum ada barang dipilih</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button" onclick="location.reload()" class="px-6 py-2 border rounded text-sm hover:bg-gray-50">Batal</button>
                        <button type="submit" name="simpan_peminjaman" class="px-6 py-2 bg-blue-600 text-white rounded text-sm font-bold shadow-md hover:bg-blue-700 transition">
                            Simpan
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <script>
        let selectedItems = [];

        function addItem(id, nama, kategori) {
            if (!selectedItems.find(item => item.id === id)) {
                selectedItems.push({ id, nama, kategori });
                const cb = document.getElementById('check-' + id);
                if(cb) cb.checked = true;
                renderTable();
            }
        }

        function toggleItem(id, nama, kategori) {
            const checkbox = document.getElementById('check-' + id);
            if (checkbox.checked) {
                selectedItems.push({ id, nama, kategori });
            } else {
                selectedItems = selectedItems.filter(item => item.id !== id);
            }
            renderTable();
        }

        function removeItem(id) {
            selectedItems = selectedItems.filter(item => item.id !== id);
            const cb = document.getElementById('check-' + id);
            if(cb) cb.checked = false;
            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('selectedTableBody');
            const countSpan = document.getElementById('countItems');
            
            if(selectedItems.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="p-4 text-center text-gray-400 italic">Belum ada barang dipilih</td></tr>';
                countSpan.innerText = '0';
                return;
            }

            tbody.innerHTML = '';
            selectedItems.forEach((item, index) => {
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">${index + 1}</td>
                        <td class="p-3 font-medium">${item.nama}</td>
                        <td class="p-3 text-gray-500">${item.kategori}</td>
                        <td class="p-3 text-center">
                            <input type="hidden" name="selected_items[]" value="${item.id}">
                            <button type="button" onclick="removeItem(${item.id})" class="text-red-400 hover:text-red-600 transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            countSpan.innerText = selectedItems.length;
        }

        function searchTable() {
            let input = document.getElementById("searchInput").value.toUpperCase();
            let table = document.getElementById("tableBarang");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let tdName = tr[i].getElementsByTagName("td")[1];
                if (tdName) {
                    let txtValue = tdName.textContent || tdName.innerText;
                    tr[i].style.display = txtValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
                }
            }
        }
    </script>
</body>
</html>
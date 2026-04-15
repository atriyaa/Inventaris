<?php
// 1. Mulai session (harus dijalankan sebelum bisa menghapus)
session_start();

// 2. Hapus semua data session yang tersimpan
$_SESSION = []; // Mengosongkan array session
session_unset(); // Membebaskan memori session
session_destroy(); // Menghancurkan session di server

// 3. Tambahkan pesan sukses (opsional) agar di login.php muncul notifikasi
header("Location: ../login.php?pesan=logout");
exit;
?>
<ul class="sidebar-menu">
    <li class="menu-header">MAIN NAVIGATION</li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>"><a href="dashboard.php"><span> &nbsp; DASHBOARD</span></a></li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'pinjam.php' ? 'active' : '' ?>"><a href="../pinjam.php"><span> &nbsp; PINJAM BARANG</span></a></li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'peminjaman.php' ? 'active' : '' ?>"><a href="peminjaman.php"><span> &nbsp; PEMINJAMAN AKTIF</span></a></li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'history_peminjaman.php' ? 'active' : '' ?>"><a href="history_peminjaman.php"><span> &nbsp; HISTORY PEMINJAMAN</span></a></li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'perawatan.php' ? 'active' : '' ?>"><a href="perawatan.php"><span> &nbsp; PERAWATAN</span></a></li>
</ul>
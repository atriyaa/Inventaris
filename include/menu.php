<ul class="sidebar-menu">
    <li class="menu-header">MAIN NAVIGATION</li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>"><a href="dashboard.php"><span> &nbsp; DASHBOARD</span></a></li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'inventaris.php' ? 'active' : '' ?>"><a href="inventaris.php"><span> &nbsp; INVENTARIS</span></a></li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'peminjaman_barang.php' ? 'active' : '' ?>"><a href="peminjaman_barang.php"><span> &nbsp; PEMINJAMAN BARANG</span></a></li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'perawatan.php' ? 'active' : '' ?>"><a href="perawatan.php"><span> &nbsp; PERAWATAN</span></a></li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'pinjam.php' ? 'active' : '' ?>"><a href="../pinjam.php"><span> &nbsp; KERUSAKAN</span></a></li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'license.php' ? 'active' : '' ?>"><a href="../license.php"><span> &nbsp; LICENSE SOFTWARE</span></a></li>
</ul>
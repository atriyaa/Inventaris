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
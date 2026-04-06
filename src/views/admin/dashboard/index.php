<section class="section">
    <div class="section__header">
        <h1>Dashboard</h1>
        <p>Thong ke tong quan he thong.</p>
    </div>
    <div class="stats-grid">
        <article class="stat-card"><h3>Tong don hang</h3><strong><?php echo (int) $stats['orders']; ?></strong></article>
        <article class="stat-card"><h3>Tong doanh thu</h3><strong><?php echo e(format_price((float) $stats['revenue'])); ?></strong></article>
        <article class="stat-card"><h3>So nguoi dung</h3><strong><?php echo (int) $stats['users']; ?></strong></article>
        <article class="stat-card"><h3>So san pham</h3><strong><?php echo (int) $stats['products']; ?></strong></article>
    </div>

    <div class="chart-card section">
        <h2>Doanh thu theo ngay</h2>
        <?php if ($revenueSeries): ?>
            <table>
                <thead><tr><th>Ngay</th><th>Doanh thu</th></tr></thead>
                <tbody>
                <?php foreach ($revenueSeries as $point): ?>
                    <tr><td><?php echo e($point['label']); ?></td><td><?php echo e(format_price((float) $point['revenue'])); ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state"><p>Chua co du lieu doanh thu.</p></div>
        <?php endif; ?>
    </div>
</section>

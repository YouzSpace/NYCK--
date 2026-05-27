<?php
ob_start();
?>
<section class="page-header page-header-compact">
    <div class="page-heading">
        <h1>仪表盘</h1>
    </div>
</section>

<?php if (isset($message) && $message !== '') : ?>
    <div class="alert <?= ($messageType ?? 'success') === 'error' ? 'alert-error' : 'alert-success' ?>">
        <p><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
<?php endif; ?>

<section class="stats-grid">
    <article class="stat-card">
        <span class="stat-kicker">Links</span>
        <div class="stat-number"><?= (int) ($stats['total_links'] ?? 0) ?></div>
        <div class="stat-label">短链接总数</div>
        <span class="stat-meta">当前账号可管理的短链接数量。</span>
    </article>
    <article class="stat-card">
        <span class="stat-kicker">PV</span>
        <div class="stat-number"><?= (int) ($stats['total_pv'] ?? 0) ?></div>
        <div class="stat-label">累计访问量</div>
        <span class="stat-meta">所有短链接累计产生的访问次数。</span>
    </article>
    <article class="stat-card">
        <span class="stat-kicker">7 Days</span>
        <div class="stat-number"><?= (int) ($stats['recent_links'] ?? 0) ?></div>
        <div class="stat-label">近 7 天新增</div>
        <span class="stat-meta">最近 7 天创建的短链接数量。</span>
    </article>
</section>
<?php
$content = (string) ob_get_clean();
$title = '仪表盘';
require dirname(__DIR__) . '/partials/layout.php';

<?php
/** @var array<int, array<string, mixed>> $links */

ob_start();

$filterUserId = (string) ($_GET['user_id'] ?? '');
$filteredUsername = '';

if ($filterUserId !== '') {
    // 获取被筛选用户的用户名
    $filteredUser = null;
    foreach ($users ?? [] as $u) {
        if ((string) ($u['id'] ?? '') === $filterUserId) {
            $filteredUser = $u;
            break;
        }
    }
    if ($filteredUser !== null) {
        $filteredUsername = (string) ($filteredUser['username'] ?? '');
    }
}
?>
<section class="page-header page-header-compact">
    <div class="page-heading">
        <h1><?= $filteredUsername !== '' ? htmlspecialchars($filteredUsername, ENT_QUOTES, 'UTF-8') . ' 的短链' : '短链管理' ?></h1>
    </div>
    <?php if ($filterUserId !== '') : ?>
        <div class="page-actions">
            <a class="btn btn-ghost" href="/index.php?page=users">返回用户管理</a>
            <a class="btn btn-ghost" href="/index.php?page=links">查看全部短链</a>
        </div>
    <?php endif; ?>
</section>

<?php if (isset($message) && $message !== '') : ?>
    <div class="alert <?= ($messageType ?? 'success') === 'error' ? 'alert-error' : 'alert-success' ?>">
        <p><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
<?php endif; ?>

<section class="table-card">
    <div class="toolbar">
        <div class="toolbar-copy">
            <h2>短链列表</h2>
        </div>
        <div class="toolbar-actions">
            <?php if ($filterUserId === '') : ?>
                <a class="btn btn-ghost btn-sm" href="/index.php?page=links&action=create">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                    创建短链
                </a>
            <?php endif; ?>
            <span class="filter-pill">总量 <?= count($links) ?></span>
        </div>
    </div>

    <?php if ($links === []) : ?>
        <div class="empty-state">
            <h3>还没有短链数据</h3>
            <p><?= $filterUserId !== '' ? '该用户暂无短链。' : '可以先创建一条新的短链。' ?></p>
        </div>
    <?php else : ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                <tr>
                    <th>短链</th>
                    <th>目标地址</th>
                    <th>访问量</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($links as $link) : ?>
                    <?php
                    $status = (string) ($link['status'] ?? 'disabled');
                    $expiresAt = (string) ($link['expires_at'] ?? '');
                    $codeText = (string) ($link['code'] ?? '');
                    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                    $shortUrl = $scheme . '://' . $host . '/' . $codeText;
                    $linkId = (string) ($link['id'] ?? '');
                    $encodedLinkId = htmlspecialchars($linkId, ENT_QUOTES, 'UTF-8');
                    $encodedShortUrl = htmlspecialchars($shortUrl, ENT_QUOTES, 'UTF-8');
                    $targetUrl = (string) ($link['target_url'] ?? '');
                    $displayTarget = mb_strlen($targetUrl) > 40 ? mb_substr($targetUrl, 0, 40) . '...' : $targetUrl;
                    ?>
                    <tr>
                        <td data-label="短链">
                            <div class="table-title-cell short-code-cell">
                                <strong><?= $encodedShortUrl ?></strong>
                                <button
                                    class="copy-link-btn"
                                    type="button"
                                    data-copy-text="<?= $encodedShortUrl ?>"
                                >复制</button>
                            </div>
                        </td>
                        <td data-label="目标地址">
                            <span title="<?= htmlspecialchars($targetUrl, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($displayTarget, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td data-label="访问量">
                            <strong><?= (int) ($link['pv'] ?? 0) ?></strong>
                        </td>
                        <td data-label="状态">
                            <?php if ($status === 'active') : ?>
                                <?php if ($expiresAt !== '') : ?>
                                    <span class="status-badge status-pending">
                                        <?= htmlspecialchars($expiresAt, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                <?php else : ?>
                                    <span class="status-badge status-active">启用</span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span class="status-badge status-disabled">停用</span>
                            <?php endif; ?>
                        </td>
                        <td data-label="操作">
                            <div class="inline-actions">
                                <a
                                    class="btn btn-secondary btn-sm"
                                    href="/index.php?page=links&action=edit&id=<?= $encodedLinkId ?>"
                                >编辑</a>
                                <?php if ($status === 'active') : ?>
                                    <form
                                        method="post"
                                        action="/index.php?page=links"
                                        onsubmit="return confirm('确认停用该短链吗？');"
                                    >
                                        <input type="hidden" name="form_action" value="disable">
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $encodedLinkId ?>"
                                        >
                                        <button class="btn btn-ghost btn-sm" type="submit">停用</button>
                                    </form>
                                <?php endif; ?>
                                <form
                                    method="post"
                                    action="/index.php?page=links"
                                    onsubmit="return confirm('确认删除该短链吗？');"
                                >
                                    <input type="hidden" name="form_action" value="delete">
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $encodedLinkId ?>"
                                    >
                                    <button class="btn btn-ghost btn-sm" type="submit">删除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php
$content = (string) ob_get_clean();
$title = $filteredUsername !== '' ? $filteredUsername . ' 的短链' : '短链管理';
require dirname(__DIR__) . '/partials/layout.php';

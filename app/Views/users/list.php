<?php
/** @var array<int, array<string, mixed>> $users */

ob_start();
?>
<section class="page-header page-header-compact">
    <div class="page-heading">
        <h1>用户管理</h1>
    </div>
</section>

<?php if (isset($message) && $message !== '') : ?>
    <div class="alert <?= ($messageType ?? 'success') === 'error' ? 'alert-error' : 'alert-success' ?>">
        <p><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
<?php endif; ?>

<section class="table-card">
    <div class="toolbar">
        <div class="toolbar-copy">
            <h2>账号列表</h2>
        </div>
        <div class="toolbar-actions">
            <a class="btn btn-ghost btn-sm" href="/index.php?page=users&action=create">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/></svg>
                新增账号
            </a>
            <span class="filter-pill">总数 <?= count($users) ?></span>
        </div>
    </div>

    <?php if ($users === []) : ?>
        <div class="empty-state">
            <h3>暂无账号</h3>
            <p>系统默认保留管理员账号，可新增其他成员。</p>
        </div>
    <?php else : ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                <tr>
                    <th>用户名</th>
                    <th>角色</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user) : ?>
                    <?php
                    $status = (string) ($user['status'] ?? 'disabled');
                    $role = (string) ($user['role'] ?? 'user');
                    $userId = (string) ($user['id'] ?? '');
                    $encodedUserId = htmlspecialchars($userId, ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td data-label="用户名">
                            <strong>
                                <?= htmlspecialchars((string) ($user['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            </strong>
                        </td>
                        <td data-label="角色">
                            <span class="status-badge <?= $role === 'admin' ? 'status-active' : 'status-pending' ?>">
                                <?= htmlspecialchars($role === 'admin' ? '管理员' : '普通用户', ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td data-label="状态">
                            <span class="status-badge <?= $status === 'active' ? 'status-active' : 'status-disabled' ?>">
                                <?= htmlspecialchars($status === 'active' ? '启用' : '停用', ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td data-label="操作">
                            <div class="inline-actions">
                                <a
                                    class="btn btn-secondary btn-sm"
                                    href="/index.php?page=links&user_id=<?= $encodedUserId ?>"
                                >查看链接</a>
                                <a
                                    class="btn btn-secondary btn-sm"
                                    href="/index.php?page=users&action=edit&id=<?= $encodedUserId ?>"
                                >编辑</a>
                                <?php if ($status === 'active') : ?>
                                    <form
                                        method="post"
                                        action="/index.php?page=users"
                                        onsubmit="return confirm('确认停用该账号吗？');"
                                    >
                                        <input type="hidden" name="form_action" value="disable">
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $encodedUserId ?>"
                                        >
                                        <button class="btn btn-ghost btn-sm" type="submit">停用</button>
                                    </form>
                                <?php endif; ?>
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
$title = '用户管理';
require dirname(__DIR__) . '/partials/layout.php';

<?php
/** @var array<string, mixed> $formUser */
/** @var array<int, string> $formErrors */
/** @var string $formMode */

ob_start();
$username = (string) ($formUser['username'] ?? '');
$role = (string) ($formUser['role'] ?? 'user');
$status = (string) ($formUser['status'] ?? 'active');
$formTitle = $formMode === 'edit' ? '编辑账号' : '新增账号';
$formSubmitLabel = $formMode === 'edit' ? '保存修改' : '创建账号';
?>
<section class="page-header">
    <div class="page-heading">
        <h1><?= htmlspecialchars($formTitle, ENT_QUOTES, 'UTF-8') ?></h1>
        <p>维护账号基础资料、登录密码、角色和启用状态。</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-ghost" href="/index.php?page=users">返回列表</a>
    </div>
</section>

<?php if ($formErrors !== []) : ?>
    <div class="alert alert-error">
        <?php foreach ($formErrors as $error) : ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form class="card form-card" method="post" action="/index.php?page=users">
    <input type="hidden" name="form_action" value="<?= $formMode === 'edit' ? 'update' : 'create' ?>">
    <?php if ($formMode === 'edit') : ?>
        <input
            type="hidden"
            name="id"
            value="<?= htmlspecialchars((string) ($formUser['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
        >
    <?php endif; ?>

    <h2 class="form-section-title">账号信息</h2>
    <p class="form-section-copy">建议优先保留默认管理员，再按实际协作场景新增普通账号。</p>
    <div class="form-divider"></div>

    <div class="form-grid">
        <div class="form-row">
            <div class="form-group">
                <label for="username">用户名</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    placeholder="例如：admin"
                    value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
                    <?= $formMode === 'edit' ? 'readonly' : 'required' ?>
                >
                <span class="field-hint">用于后台登录与成员识别，建议使用易读英文或拼音。</span>
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="<?= $formMode === 'edit' ? '留空表示不修改密码' : '至少 6 位密码' ?>"
                    <?= $formMode === 'create' ? 'required' : '' ?>
                >
                <span class="field-hint">建议定期更新密码，避免账号被他人使用。</span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="role">角色</label>
                <select id="role" name="role">
                    <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>普通用户</option>
                    <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>管理员</option>
                </select>
                <span class="field-hint">管理员可管理用户与系统配置，普通账号聚焦内容操作。</span>
            </div>
            <div class="form-group">
                <label for="status">状态</label>
                <select id="status" name="status">
                    <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>启用</option>
                    <option value="disabled" <?= $status === 'disabled' ? 'selected' : '' ?>>停用</option>
                </select>
                <span class="field-hint">停用后账号不可继续登录，但历史数据仍保留。</span>
            </div>
        </div>
    </div>

    <div class="form-actions" style="margin-top: 24px;">
        <button class="btn btn-primary" type="submit">
            <?= htmlspecialchars($formSubmitLabel, ENT_QUOTES, 'UTF-8') ?>
        </button>
        <a class="btn btn-secondary" href="/index.php?page=users">取消返回</a>
    </div>
</form>
<?php
$content = (string) ob_get_clean();
$title = $formTitle;
require dirname(__DIR__) . '/partials/layout.php';

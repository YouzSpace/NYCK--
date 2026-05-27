<?php
/** @var array<string, mixed> $formLink */
/** @var array<int, string> $formErrors */
/** @var string $formMode */

ob_start();
$formTitle = $formMode === 'edit' ? '编辑短链' : '创建短链';
$formSubmitLabel = $formMode === 'edit' ? '保存修改' : '创建短链';
$targetUrl = (string) ($formLink['target_url'] ?? '');
$customCode = (string) ($formLink['custom_code'] ?? $formLink['code'] ?? '');
$expiresAtRaw = (string) ($formLink['expires_at'] ?? '');
$expiresAtValue = '';
if ($expiresAtRaw !== '') {
    $timestamp = strtotime($expiresAtRaw);
    if ($timestamp !== false) {
        $expiresAtValue = date('Y-m-d\TH:i', $timestamp);
    }
}
?>
<section class="page-header">
    <div class="page-heading">
        <h1><?= htmlspecialchars($formTitle, ENT_QUOTES, 'UTF-8') ?></h1>
        <p>配置目标地址、短码和过期时间。</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-ghost" href="/index.php?page=links">返回列表</a>
    </div>
</section>

<?php if ($formErrors !== []) : ?>
    <div class="alert alert-error">
        <?php foreach ($formErrors as $error) : ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form class="card form-card" method="post" action="/index.php?page=links">
    <input type="hidden" name="form_action" value="<?= $formMode === 'edit' ? 'update' : 'create' ?>">
    <?php if ($formMode === 'edit') : ?>
        <input
            type="hidden"
            name="id"
            value="<?= htmlspecialchars((string) ($formLink['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
        >
    <?php endif; ?>

    <h2 class="form-section-title">短链配置</h2>
    <p class="form-section-copy">可留空自动生成短码，也可手动填写自定义短码。</p>
    <div class="form-divider"></div>

    <div class="form-grid">
        <div class="form-group">
            <label for="target_url">目标链接</label>
            <input
                id="target_url"
                type="url"
                name="target_url"
                placeholder="https://example.com/landing-page"
                value="<?= htmlspecialchars($targetUrl, ENT_QUOTES, 'UTF-8') ?>"
                required
            >
            <span class="field-hint">填写用户最终跳转的地址，建议使用完整协议头。</span>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="custom_code">自定义短码</label>
                <input
                    id="custom_code"
                    type="text"
                    name="custom_code"
                    placeholder="留空则自动生成"
                    value="<?= htmlspecialchars($customCode, ENT_QUOTES, 'UTF-8') ?>"
                    <?= $formMode === 'edit' ? 'readonly' : '' ?>
                >
                <span class="field-hint">建议使用简短、可读、便于传播的字符组合。</span>
            </div>
            <div class="form-group">
                <label for="expires_at">过期时间</label>
                <input
                    id="expires_at"
                    type="datetime-local"
                    name="expires_at"
                    value="<?= htmlspecialchars($expiresAtValue, ENT_QUOTES, 'UTF-8') ?>"
                >
                <span class="field-hint">不填写则默认为长期有效，适合常驻入口。</span>
            </div>
        </div>
    </div>

    <div class="info-card" style="margin-top: 20px; padding: 18px 20px;">
        <div class="card-title-row">
            <div>
                <h3 class="card-title">配置建议</h3>
                <p class="card-desc">可按业务场景设置短码规则，避免重复与难记编码。</p>
            </div>
            <span class="badge">Best Practice</span>
        </div>
        <div class="kv-list">
            <div class="kv-item">
                <strong>常驻入口</strong>
                <span>留空过期时间，保持长期可访问</span>
            </div>
            <div class="kv-item">
                <strong>活动入口</strong>
                <span>设置到期时间，活动结束后自动失效</span>
            </div>
        </div>
    </div>

    <div class="form-actions" style="margin-top: 24px;">
        <button class="btn btn-primary" type="submit">
            <?= htmlspecialchars($formSubmitLabel, ENT_QUOTES, 'UTF-8') ?>
        </button>
        <a class="btn btn-secondary" href="/index.php?page=links">取消返回</a>
    </div>
</form>
<?php
$content = (string) ob_get_clean();
$title = $formTitle;
require dirname(__DIR__) . '/partials/layout.php';

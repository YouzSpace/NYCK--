<?php

/** @var int $statusCode */
/** @var string $title */
/** @var string $message */
/** @var string $hint */
/** @var string $actionLabel */
/** @var string $actionHref */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="auth-page">
<div class="auth-shell auth-shell-compact">
    <section class="auth-brand-panel error-panel">
        <div>
            <span class="auth-badge">Status <?= (int) $statusCode ?></span>
            <h1 class="auth-brand-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="auth-brand-copy"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div class="auth-brand-footer">
            <span>Short URL Admin</span>
            <span>Production Runtime</span>
        </div>
    </section>

    <section class="auth-card error-card">
        <div class="login-eyebrow">访问提示</div>
        <h2 class="login-title">当前页面暂时不可用</h2>
        <p class="login-subtitle"><?= htmlspecialchars($hint, ENT_QUOTES, 'UTF-8') ?></p>
        <div class="form-actions" style="margin-top: 24px;">
            <a class="btn btn-primary btn-block" href="<?= htmlspecialchars($actionHref, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($actionLabel, ENT_QUOTES, 'UTF-8') ?>
            </a>
        </div>
    </section>
</div>
</body>
</html>

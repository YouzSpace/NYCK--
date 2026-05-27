<?php
/** @var array<int, string> $errors */
/** @var array<string, string> $old */
$username = (string) ($old['username'] ?? '');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册账号</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="auth-page">
<div class="auth-shell auth-shell-single">
    <section class="auth-card">
        <h1 class="login-title">创建账号</h1>
        <p class="login-subtitle">普通账号只能管理自己创建的短链。</p>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error) : ?>
                    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="auth-form" method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="username">用户名</label>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="请输入账号"
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="password">密码</label>
                    <input id="password" type="password" name="password" placeholder="至少 6 位密码" required>
                </div>
                <div class="form-group">
                    <label for="password_confirm">确认密码</label>
                    <input
                        id="password_confirm"
                        type="password"
                        name="password_confirm"
                        placeholder="请再次输入密码"
                        required
                    >
                </div>
                <button class="btn btn-primary btn-block" type="submit">注册</button>
            </div>
        </form>

        <div class="auth-link-row">
            <a href="/login.php">已有账号？去登录</a>
            <a href="/home.php">返回首页</a>
        </div>
    </section>
</div>
</body>
</html>

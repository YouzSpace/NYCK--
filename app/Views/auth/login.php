<?php
/** @var array<int, string> $errors */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="auth-page">
<div class="auth-shell auth-shell-single">
    <section class="auth-card">
        <h2 class="login-title">登录账号</h2>
        <p class="login-subtitle">进入后可以创建和管理短链。</p>

        <?php if (!empty($message)) : ?>
            <div class="alert alert-success">
                <p><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        <?php endif; ?>

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
                    <input id="username" type="text" name="username" placeholder="请输入账号" required>
                </div>
                <div class="form-group">
                    <label for="password">密码</label>
                    <input id="password" type="password" name="password" placeholder="请输入登录密码" required>
                </div>
                <button class="btn btn-primary btn-block" type="submit">登录</button>
            </div>
        </form>

        <div class="auth-link-row">
            <a href="/register.php">没有账号？去注册</a>
            <a href="/home.php">返回首页</a>
        </div>
    </section>
</div>
</body>
</html>

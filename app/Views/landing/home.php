<?php
/** @var array{login: string, register: string} $entryLinks */
$loginUrl = htmlspecialchars($entryLinks['login'], ENT_QUOTES, 'UTF-8');
$registerUrl = htmlspecialchars($entryLinks['register'], ENT_QUOTES, 'UTF-8');

$icon = static function (string $name): string {
    $icons = [
        'link' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 1 0-7l2-2a5 5 0 1 1 7 7l-1.5 1.5"></path><path d="M14 11a5 5 0 0 1 0 7l-2 2a5 5 0 1 1-7-7L6.5 11.5"></path></svg>',
        'chart' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 20V10"></path><path d="M12 20V4"></path><path d="M6 20v-6"></path></svg>',
        'clock' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>',
        'users' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M23 11h-6"></path></svg>',
        'arrow' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>',
    ];
    return $icons[$name] ?? $icons['link'];
};
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NYCK - 短链接管理系统</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        .home-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .home-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 0.5px solid var(--color-separator);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .home-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.03em;
            color: var(--color-text);
        }

        .home-logo-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3);
        }

        .home-logo-icon svg {
            width: 18px;
            height: 18px;
        }

        .home-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Hero */
        .home-hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 40px;
            text-align: center;
        }

        .home-hero-content {
            max-width: 720px;
        }

        .home-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            background: var(--color-primary-subtle);
            color: var(--color-primary);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 28px;
        }

        .home-hero-badge svg {
            width: 14px;
            height: 14px;
        }

        .home-hero h1 {
            font-size: clamp(40px, 6vw, 64px);
            line-height: 1.1;
            letter-spacing: -0.05em;
            margin: 0 0 20px;
            font-weight: 800;
            color: var(--color-text);
        }

        .home-hero p {
            font-size: 18px;
            line-height: 1.7;
            color: var(--color-text-secondary);
            margin: 0 0 40px;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        .home-hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .home-hero-actions .btn {
            min-height: 52px;
            padding: 14px 28px;
            font-size: 15px;
        }

        /* Features */
        .home-features {
            padding: 80px 40px;
            background: rgba(255, 255, 255, 0.5);
            border-top: 0.5px solid var(--color-separator);
        }

        .home-features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            max-width: 960px;
            margin: 0 auto;
        }

        .home-feature-card {
            padding: 32px 24px;
            border-radius: 16px;
            background: var(--color-surface);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: var(--shadow-sm);
            transition: var(--transition-base);
        }

        .home-feature-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .home-feature-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .home-feature-icon svg {
            width: 24px;
            height: 24px;
        }

        .home-feature-icon.blue {
            background: rgba(0, 122, 255, 0.1);
            color: #007AFF;
        }

        .home-feature-icon.green {
            background: rgba(52, 199, 89, 0.1);
            color: #34C759;
        }

        .home-feature-icon.orange {
            background: rgba(255, 149, 0, 0.1);
            color: #FF9500;
        }

        .home-feature-icon.purple {
            background: rgba(88, 86, 214, 0.1);
            color: #5856D6;
        }

        .home-feature-card h3 {
            font-size: 17px;
            font-weight: 700;
            margin: 0 0 10px;
            letter-spacing: -0.02em;
        }

        .home-feature-card p {
            font-size: 14px;
            line-height: 1.6;
            color: var(--color-text-secondary);
            margin: 0;
        }

        /* How it works */
        .home-how {
            padding: 80px 40px;
        }

        .home-how-title {
            text-align: center;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.04em;
            margin: 0 0 60px;
        }

        .home-how-steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 48px;
            max-width: 800px;
            margin: 0 auto;
        }

        .home-step {
            text-align: center;
        }

        .home-step-icon {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            margin: 0 auto 24px;
            transition: var(--transition-base);
        }

        .home-step-icon svg {
            width: 28px;
            height: 28px;
        }

        .home-step-icon-blue {
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.12), rgba(0, 122, 255, 0.06));
            color: #007AFF;
            box-shadow: 0 8px 24px rgba(0, 122, 255, 0.15);
        }

        .home-step-icon-green {
            background: linear-gradient(135deg, rgba(52, 199, 89, 0.12), rgba(52, 199, 89, 0.06));
            color: #34C759;
            box-shadow: 0 8px 24px rgba(52, 199, 89, 0.15);
        }

        .home-step-icon-orange {
            background: linear-gradient(135deg, rgba(255, 149, 0, 0.12), rgba(255, 149, 0, 0.06));
            color: #FF9500;
            box-shadow: 0 8px 24px rgba(255, 149, 0, 0.15);
        }

        .home-step:hover .home-step-icon {
            transform: translateY(-4px);
        }

        .home-step h3 {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 10px;
        }

        .home-step p {
            font-size: 14px;
            line-height: 1.6;
            color: var(--color-text-secondary);
            margin: 0;
        }

        /* Footer */
        .home-footer {
            padding: 32px 40px;
            text-align: center;
            border-top: 0.5px solid var(--color-separator);
            color: var(--color-text-tertiary);
            font-size: 13px;
        }

        /* Demo bar */
        .home-demo-bar {
            margin-top: 48px;
            padding: 24px;
            border-radius: 16px;
            background: var(--color-surface);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: var(--shadow-md);
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        .home-demo-bar-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 16px;
        }

        .home-demo-input {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            background: var(--color-bg);
            border: 1.5px solid var(--color-border);
        }

        .home-demo-input span {
            flex: 1;
            font-size: 14px;
            color: var(--color-text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .home-demo-input .btn {
            min-height: 36px;
            padding: 8px 16px;
            font-size: 12px;
            flex-shrink: 0;
        }

        .home-demo-stats {
            display: flex;
            gap: 32px;
            margin-top: 16px;
            justify-content: center;
        }

        .home-demo-stat {
            text-align: center;
        }

        .home-demo-stat strong {
            display: block;
            font-size: 20px;
            font-weight: 800;
            color: var(--color-primary);
        }

        .home-demo-stat span {
            font-size: 12px;
            color: var(--color-text-tertiary);
        }

        @media (max-width: 768px) {
            .home-header {
                padding: 16px 20px;
            }

            .home-hero {
                padding: 60px 20px;
            }

            .home-hero h1 {
                font-size: 36px;
            }

            .home-hero p {
                font-size: 16px;
            }

            .home-features,
            .home-how {
                padding: 60px 20px;
            }

            .home-how-steps {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .home-demo-stats {
                gap: 24px;
            }
        }
    </style>
</head>
<body class="home-page">
    <header class="home-header">
        <a class="home-logo" href="/home.php">
            <img src="/assets/images/logo.svg" alt="NYCK Logo" height="36">
        </a>
        <nav class="home-nav">
            <a class="btn btn-ghost btn-sm" href="<?= $loginUrl ?>">登录</a>
            <a class="btn btn-primary btn-sm" href="<?= $registerUrl ?>">注册</a>
        </nav>
    </header>

    <main>
        <section class="home-hero">
            <div class="home-hero-content">
                <span class="home-hero-badge">
                    <?= $icon('link') ?>
                    短链接工具
                </span>
                <h1>把长链接收短，<br>轻松分享与追踪</h1>
                <p>将冗长的网址转换为简洁短链，支持访问统计、过期控制和多账号协作，让链接管理更高效。</p>
                <div class="home-hero-actions">
                    <a class="btn btn-primary" href="<?= $registerUrl ?>">免费注册</a>
                    <a class="btn btn-ghost" href="<?= $loginUrl ?>">登录使用</a>
                </div>

                <div class="home-demo-bar">
                    <div class="home-demo-bar-title">短链预览</div>
                    <div class="home-demo-input">
                        <span>https://shop.co/item</span>
                        <button class="btn btn-primary btn-sm" type="button">生成</button>
                    </div>
                    <div class="home-demo-stats">
                        <div class="home-demo-stat">
                            <strong>6</strong>
                            <span>字符短码</span>
                        </div>
                        <div class="home-demo-stat">
                            <strong>302</strong>
                            <span>快速跳转</span>
                        </div>
                        <div class="home-demo-stat">
                            <strong>∞</strong>
                            <span>永久有效</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-features">
            <div class="home-features-grid">
                <div class="home-feature-card">
                    <div class="home-feature-icon blue"><?= $icon('link') ?></div>
                    <h3>短链生成</h3>
                    <p>自动生成 6 位短码，也支持自定义短码，便于记忆和传播。</p>
                </div>
                <div class="home-feature-card">
                    <div class="home-feature-icon green"><?= $icon('chart') ?></div>
                    <h3>访问统计</h3>
                    <p>实时记录每条短链的累计访问量，快速了解链接使用情况。</p>
                </div>
                <div class="home-feature-card">
                    <div class="home-feature-icon orange"><?= $icon('clock') ?></div>
                    <h3>过期控制</h3>
                    <p>为短链接设置有效期，到期后自动停止访问，适合活动场景。</p>
                </div>
                <div class="home-feature-card">
                    <div class="home-feature-icon purple"><?= $icon('users') ?></div>
                    <h3>多账号协作</h3>
                    <p>支持管理员和普通用户分工管理，各司其职，数据隔离。</p>
                </div>
            </div>
        </section>

        <section class="home-how">
            <h2 class="home-how-title">三步开始使用</h2>
            <div class="home-how-steps">
                <div class="home-step">
                    <div class="home-step-icon home-step-icon-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M23 11h-6"></path></svg>
                    </div>
                    <h3>注册账号</h3>
                    <p>创建您的专属账号，登录后台管理系统。</p>
                </div>
                <div class="home-step">
                    <div class="home-step-icon home-step-icon-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 1 0-7l2-2a5 5 0 1 1 7 7l-1.5 1.5"></path><path d="M14 11a5 5 0 0 1 0 7l-2 2a5 5 0 1 1-7-7L6.5 11.5"></path></svg>
                    </div>
                    <h3>创建短链</h3>
                    <p>粘贴长链接，生成短码，可设置过期时间。</p>
                </div>
                <div class="home-step">
                    <div class="home-step-icon home-step-icon-orange">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 20V10"></path><path d="M12 20V4"></path><path d="M6 20v-6"></path></svg>
                    </div>
                    <h3>分享追踪</h3>
                    <p>分享短链，随时查看访问量和链接状态。</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="home-footer">
        <p>NYCK · 让链接更简洁，让分享更高效</p>
    </footer>
</body>
</html>

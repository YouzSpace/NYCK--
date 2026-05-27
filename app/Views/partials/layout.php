<?php

/** @var string $title */
/** @var string $content */
$currentPage = (string) ($_GET['page'] ?? 'dashboard');
$currentUser = isset($currentUser) && is_array($currentUser) ? $currentUser : null;
$currentRole = (string) ($currentUser['role'] ?? 'user');
$navItems = [
    'dashboard' => ['label' => '仪表盘', 'href' => '/index.php', 'icon' => 'home'],
    'links' => ['label' => '短链管理', 'href' => '/index.php?page=links', 'icon' => 'link'],
];

if ($currentRole === 'admin') {
    $navItems['users'] = ['label' => '用户管理', 'href' => '/index.php?page=users', 'icon' => 'users'];
}
$pageDescriptions = [
    'dashboard' => '短链数量、访问量和最近新增。',
    'links' => '创建、编辑、停用和删除短链。',
    'users' => '维护账号、角色和启用状态。',
];
$activeKey = array_key_exists($currentPage, $navItems) ? $currentPage : 'dashboard';
$pageDescription = $pageDescriptions[$activeKey];
$roleLabels = [
    'admin' => '管理员',
    'user' => '普通用户',
];

$renderIcon = static function (string $name): string {
    $icons = [
        'home' => implode('', [
            '<svg viewBox="0 0 24 24" aria-hidden="true">',
            '<path d="M3 10.5L12 3l9 7.5"></path>',
            '<path d="M5 9.5V21h14V9.5"></path>',
            '</svg>',
        ]),
        'link' => implode('', [
            '<svg viewBox="0 0 24 24" aria-hidden="true">',
            '<path d="M10 13a5 5 0 0 1 0-7l2-2a5 5 0 1 1 7 7l-1.5 1.5"></path>',
            '<path d="M14 11a5 5 0 0 1 0 7l-2 2a5 5 0 1 1-7-7L6.5 11.5"></path>',
            '</svg>',
        ]),
        'users' => implode('', [
            '<svg viewBox="0 0 24 24" aria-hidden="true">',
            '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>',
            '<circle cx="8.5" cy="7" r="4"></circle>',
            '<path d="M20 8v6"></path>',
            '<path d="M23 11h-6"></path>',
            '</svg>',
        ]),
        'logout' => implode('', [
            '<svg viewBox="0 0 24 24" aria-hidden="true">',
            '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>',
            '<path d="M10 17l5-5-5-5"></path>',
            '<path d="M15 12H3"></path>',
            '</svg>',
        ]),
        'spark' => implode('', [
            '<svg viewBox="0 0 24 24" aria-hidden="true">',
            '<path d="M12 3l1.8 4.7L18.5 9.5l-4.7 1.8L12 16l-1.8-4.7L5.5 9.5l4.7-1.8L12 3z"></path>',
            '</svg>',
        ]),
    ];

    return $icons[$name] ?? $icons['spark'];
};
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script defer src="/assets/js/admin.js"></script>
</head>
<body class="admin-body">
<div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="sidebar" id="admin-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <span class="sidebar-logo-mark">
                    <img src="/assets/images/icon.svg" alt="NYCK" width="24" height="24">
                </span>
                <span class="sidebar-logo-copy">
                    <strong>NYCK</strong>
                    <span>Console</span>
                </span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <?php foreach ($navItems as $key => $item) : ?>
                <a
                    class="nav-item<?= $activeKey === $key ? ' active' : '' ?>"
                    href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
                >
                    <?= $renderIcon($item['icon']) ?>
                    <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a class="nav-item" href="/logout.php">
                <?= $renderIcon('logout') ?>
                <span>退出登录</span>
            </a>
        </div>
    </aside>

    <div class="main-frame">
        <header class="topbar">
            <div class="topbar-inner">
                <div class="topbar-left">
                    <button
                        class="menu-toggle"
                        type="button"
                        aria-label="打开导航菜单"
                        aria-controls="admin-sidebar"
                        aria-expanded="false"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="topbar-title">
                        <strong><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></strong>
                        <span><?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>
                <div class="topbar-user">
                    <div class="topbar-user-meta">
                        <?php $displayName = (string) ($currentUser['username'] ?? '当前用户'); ?>
                        <?php $displayRole = $roleLabels[$currentRole] ?? '普通用户'; ?>
                        <strong><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></strong>
                        <span><?= htmlspecialchars($displayRole, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <?= $content ?>
        </main>
    </div>
</div>
</body>
</html>

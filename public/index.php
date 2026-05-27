<?php

declare(strict_types=1);

$container = require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/tests/bootstrap.php';
require dirname(__DIR__) . '/public_assets.php';

use ShortUrl\Controllers\DashboardController;
use ShortUrl\Controllers\LinkController;
use ShortUrl\Controllers\UserController;
use ShortUrl\Middleware\AuthMiddleware;
use ShortUrl\Middleware\RoleMiddleware;
use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\LinkRepository;
use ShortUrl\Repositories\UserRepository;
use ShortUrl\Services\AuthService;
use ShortUrl\Services\LinkService;
use ShortUrl\Services\StatsService;
use ShortUrl\Services\UserService;
use ShortUrl\Support\Response;

$jsonRepository = new JsonRepository();
$userRepository = new UserRepository(
    $jsonRepository,
    $container['paths']['users_file'],
    $container['config']['defaults']['users']
);
$linkRepository = new LinkRepository(
    $jsonRepository,
    $container['paths']['links_file'],
    $container['config']['defaults']['links']
);

$authService = new AuthService($userRepository);
(new AuthMiddleware($authService))->handle();
$currentUser = $authService->currentUser();

if ($currentUser === null) {
    Response::redirect('/login.php');
}

$userService = new UserService($userRepository);
$linkService = new LinkService($linkRepository);
$statsService = new StatsService($linkRepository);
$userController = new UserController($userService);
$linkController = new LinkController($linkService);
$dashboardController = new DashboardController($statsService);

$page = (string) ($_GET['page'] ?? 'dashboard');
$action = (string) ($_GET['action'] ?? 'list');
$message = (string) ($_GET['message'] ?? '');
$messageType = (string) ($_GET['message_type'] ?? 'success');

if ($page === 'users') {
    (new RoleMiddleware($authService))->handle('admin');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formAction = (string) ($_POST['form_action'] ?? 'create');
        $id = (string) ($_POST['id'] ?? '');

        if ($formAction === 'disable' && $id !== '') {
            $result = $userController->disable($id);
            if ($result['errors'] === []) {
                Response::redirect('/index.php?page=users&message=' . rawurlencode('账号已停用') . '&message_type=success');
            }

            $message = (string) ($result['errors'][0] ?? '账号停用失败。');
            $messageType = 'error';
            $action = 'list';
        } elseif ($formAction === 'update' && $id !== '') {
            $result = $userController->update($id, $_POST);
            if ($result['errors'] === []) {
                Response::redirect('/index.php?page=users&message=' . rawurlencode('账号信息已更新') . '&message_type=success');
            }

            $formUser = array_merge($_POST, ['id' => $id]);
            $formErrors = $result['errors'];
            require dirname(__DIR__) . '/app/Views/users/form.php';
            return;
        } else {
            $result = $userController->create($_POST);
            if ($result['errors'] === []) {
                Response::redirect('/index.php?page=users&message=' . rawurlencode('账号创建成功') . '&message_type=success');
            }

            $formUser = $_POST;
            $formErrors = $result['errors'];
            require dirname(__DIR__) . '/app/Views/users/form.php';
            return;
        }
    }

    if ($action === 'create') {
        $formMode = 'create';
        $formErrors = [];
        $formUser = [
            'username' => '',
            'role' => 'user',
            'status' => 'active',
        ];
        require dirname(__DIR__) . '/app/Views/users/form.php';
        return;
    }

    if ($action === 'edit') {
        $id = (string) ($_GET['id'] ?? '');
        $user = $id !== '' ? $userService->findUserById($id) : null;

        if ($user === null) {
            Response::redirect('/index.php?page=users&message=' . rawurlencode('未找到对应账号') . '&message_type=error');
        }

        $formMode = 'edit';
        $formErrors = [];
        $formUser = $user;
        require dirname(__DIR__) . '/app/Views/users/form.php';
        return;
    }

    $users = $userService->listUsers($_GET);
    require dirname(__DIR__) . '/app/Views/users/list.php';
    return;
}

if ($page === 'links') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formAction = (string) ($_POST['form_action'] ?? 'create');
        $id = (string) ($_POST['id'] ?? '');

        if ($formAction === 'disable' && $id !== '') {
            $result = $linkController->disable($id, $currentUser);
            if ($result['errors'] === []) {
                Response::redirect('/index.php?page=links&message=' . rawurlencode('短链已停用') . '&message_type=success');
            }

            $message = (string) ($result['errors'][0] ?? '短链停用失败。');
            $messageType = 'error';
            $action = 'list';
        } elseif ($formAction === 'delete' && $id !== '') {
            $result = $linkController->delete($id, $currentUser);
            if (($result['success'] ?? false) === true) {
                Response::redirect('/index.php?page=links&message=' . rawurlencode('短链已删除') . '&message_type=success');
            }

            $message = (string) ($result['errors'][0] ?? '短链删除失败。');
            $messageType = 'error';
            $action = 'list';
        } elseif ($formAction === 'update' && $id !== '') {
            $result = $linkController->update($id, $_POST, $currentUser);
            if ($result['errors'] === []) {
                Response::redirect('/index.php?page=links&message=' . rawurlencode('短链信息已更新') . '&message_type=success');
            }

            $formLink = array_merge($_POST, ['id' => $id]);
            $formErrors = $result['errors'];
            require dirname(__DIR__) . '/app/Views/links/form.php';
            return;
        } else {
            $result = $linkController->create($_POST, $currentUser);
            if ($result['errors'] === []) {
                Response::redirect('/index.php?page=links&message=' . rawurlencode('短链创建成功') . '&message_type=success');
            }

            $formLink = $_POST;
            $formErrors = $result['errors'];
            require dirname(__DIR__) . '/app/Views/links/form.php';
            return;
        }
    }

    if ($action === 'create') {
        $formMode = 'create';
        $formErrors = [];
        $formLink = [
            'target_url' => '',
            'custom_code' => '',
            'expires_at' => '',
            'status' => 'active',
        ];
        require dirname(__DIR__) . '/app/Views/links/form.php';
        return;
    }

    if ($action === 'edit') {
        $id = (string) ($_GET['id'] ?? '');
        $links = $linkService->listLinks([], $currentUser);
        $link = null;

        foreach ($links as $item) {
            if ((string) ($item['id'] ?? '') === $id) {
                $link = $item;
                break;
            }
        }

        if ($link === null) {
            Response::redirect('/index.php?page=links&message=' . rawurlencode('未找到对应短链') . '&message_type=error');
        }

        $formMode = 'edit';
        $formErrors = [];
        $formLink = $link;
        require dirname(__DIR__) . '/app/Views/links/form.php';
        return;
    }

    $links = $linkService->listLinks($_GET, $currentUser);

    // 如果按用户筛选，加载用户列表用于显示用户名
    if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
        $users = $userService->listUsers([]);
    }

    require dirname(__DIR__) . '/app/Views/links/list.php';
    return;
}

$stats = $statsService->getDashboardStats($currentUser);
require dirname(__DIR__) . '/app/Views/dashboard/index.php';

<?php

declare(strict_types=1);

namespace ShortUrl\Support;

class Response
{
    public static function redirect(string $url): void
    {
        header('Location: ' . $url, true, 302);
        exit;
    }

    public static function forbidden(string $message = 'Forbidden'): void
    {
        self::renderStatusPage(403, '访问受限', $message, '该短链当前不可访问，可能已被停用或没有访问权限。', '/login.php', '返回后台登录');
    }

    public static function notFound(string $message = 'Not Found'): void
    {
        self::renderStatusPage(404, '短链不存在', $message, '请检查短码是否正确，或联系后台管理员确认该链接是否已删除。', '/login.php', '返回后台登录');
    }

    public static function badRequest(string $message = 'Bad Request'): void
    {
        self::renderStatusPage(400, '请求参数无效', $message, '当前访问地址缺少必要参数，请从正确的短链入口重新进入。', '/login.php', '返回后台登录');
    }

    public static function gone(string $message = 'Gone'): void
    {
        self::renderStatusPage(410, '短链已过期', $message, '该短链已超过有效期，如需继续使用请联系后台管理员重新生成。', '/login.php', '返回后台登录');
    }

    private static function renderStatusPage(
        int $statusCode,
        string $title,
        string $message,
        string $hint,
        string $actionHref,
        string $actionLabel
    ): void {
        http_response_code($statusCode);

        $viewPath = dirname(__DIR__) . '/Views/errors/status.php';
        if (is_file($viewPath)) {
            require $viewPath;
            exit;
        }

        echo $title . ': ' . $message;
        exit;
    }
}

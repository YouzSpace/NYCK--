<?php

declare(strict_types=1);

namespace ShortUrl\Controllers;

use ShortUrl\Services\LinkService;
use Throwable;

class LinkController
{
    public function __construct(private readonly LinkService $linkService)
    {
    }

    public function list(array $filters, array $operator): void
    {
        $links = $this->linkService->listLinks($filters, $operator);
        require dirname(__DIR__) . '/Views/links/list.php';
    }

    public function create(array $input, array $operator): array
    {
        try {
            return ['link' => $this->linkService->createLink($input, $operator), 'errors' => []];
        } catch (Throwable $throwable) {
            return ['link' => null, 'errors' => [$throwable->getMessage()]];
        }
    }

    public function update(string $id, array $input, array $operator): array
    {
        try {
            return ['link' => $this->linkService->updateLink($id, $input, $operator), 'errors' => []];
        } catch (Throwable $throwable) {
            return ['link' => null, 'errors' => [$throwable->getMessage()]];
        }
    }

    public function delete(string $id, array $operator): array
    {
        try {
            $this->linkService->deleteLink($id, $operator);
            return ['success' => true, 'errors' => []];
        } catch (Throwable $throwable) {
            return ['success' => false, 'errors' => [$throwable->getMessage()]];
        }
    }

    public function disable(string $id, array $operator): array
    {
        try {
            return ['link' => $this->linkService->disableLink($id, $operator), 'errors' => []];
        } catch (Throwable $throwable) {
            return ['link' => null, 'errors' => [$throwable->getMessage()]];
        }
    }
}

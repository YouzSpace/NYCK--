<?php

declare(strict_types=1);

namespace ShortUrl\Services;

use InvalidArgumentException;
use ShortUrl\Repositories\LinkRepository;
use ShortUrl\Support\CodeGenerator;
use ShortUrl\Support\Validator;

class LinkService
{
    public function __construct(private readonly LinkRepository $linkRepository)
    {
    }

    public function createLink(array $data, array $operator): array
    {
        $targetUrl = trim((string) ($data['target_url'] ?? ''));
        $customCode = trim((string) ($data['custom_code'] ?? ''));
        $expiresAt = trim((string) ($data['expires_at'] ?? ''));

        if (!Validator::url($targetUrl)) {
            throw new InvalidArgumentException('目标链接格式不合法。');
        }

        $code = $customCode !== '' ? $this->validateShortCode($customCode) : $this->generateUniqueShortCode();

        if ($this->linkRepository->findByShortCode($code) !== null) {
            throw new InvalidArgumentException('短码已存在。');
        }

        return $this->linkRepository->create([
            'id' => uniqid('link_', true),
            'user_id' => $operator['id'],
            'code' => $code,
            'target_url' => $targetUrl,
            'status' => 'active',
            'pv' => 0,
            'expires_at' => $this->normalizeExpiry($expiresAt),
            'created_at' => date(DATE_ATOM),
            'updated_at' => date(DATE_ATOM),
        ]);
    }

    public function updateLink(string $id, array $data, array $operator): array
    {
        $link = $this->findOwnedLink($id, $operator);
        if ($link === null) {
            throw new InvalidArgumentException('短链不存在或无权访问。');
        }

        $payload = [];

        if (isset($data['target_url'])) {
            $targetUrl = trim((string) $data['target_url']);
            if (!Validator::url($targetUrl)) {
                throw new InvalidArgumentException('目标链接格式不合法。');
            }
            $payload['target_url'] = $targetUrl;
        }

        if (isset($data['status'])) {
            $status = (string) $data['status'];
            if (!in_array($status, ['active', 'disabled'], true)) {
                throw new InvalidArgumentException('短链状态不合法。');
            }
            $payload['status'] = $status;
        }

        if (array_key_exists('expires_at', $data)) {
            $payload['expires_at'] = $this->normalizeExpiry((string) $data['expires_at']);
        }

        $payload['updated_at'] = date(DATE_ATOM);

        return $this->linkRepository->update($id, $payload) ?? $link;
    }

    public function deleteLink(string $id, array $operator): bool
    {
        $link = $this->findOwnedLink($id, $operator);
        if ($link === null) {
            throw new InvalidArgumentException('短链不存在或无权访问。');
        }

        return $this->linkRepository->delete($id);
    }

    public function disableLink(string $id, array $operator): array
    {
        return $this->updateLink($id, ['status' => 'disabled'], $operator);
    }

    public function listLinks(array $filters, array $operator): array
    {
        $items = $this->linkRepository->all();

        return array_values(array_filter($items, static function (array $link) use ($filters, $operator): bool {
            if (($operator['role'] ?? 'user') !== 'admin' && ($link['user_id'] ?? null) !== ($operator['id'] ?? null)) {
                return false;
            }

            if (($filters['user_id'] ?? '') !== '' && ($link['user_id'] ?? null) !== $filters['user_id']) {
                return false;
            }

            if (($filters['status'] ?? '') !== '' && ($link['status'] ?? null) !== $filters['status']) {
                return false;
            }

            if (($filters['keyword'] ?? '') !== '') {
                $keyword = mb_strtolower((string) $filters['keyword']);
                $haystack = mb_strtolower((string) (($link['code'] ?? '') . ' ' . ($link['target_url'] ?? '')));
                if (!str_contains($haystack, $keyword)) {
                    return false;
                }
            }

            return true;
        }));
    }

    public function findByShortCode(string $code): ?array
    {
        return $this->linkRepository->findByShortCode($code);
    }

    public function validateShortCode(string $code): string
    {
        if (!Validator::shortCode($code)) {
            throw new InvalidArgumentException('短码格式不合法。');
        }

        return $code;
    }

    public function isLinkExpired(array $link): bool
    {
        $expiresAt = $link['expires_at'] ?? null;
        if (!is_string($expiresAt) || $expiresAt === '') {
            return false;
        }

        return strtotime($expiresAt) !== false && strtotime($expiresAt) < time();
    }

    public function isLinkAccessible(array $link): bool
    {
        return ($link['status'] ?? '') === 'active' && !$this->isLinkExpired($link);
    }

    private function generateUniqueShortCode(int $length = 6): string
    {
        do {
            $code = CodeGenerator::generate($length);
        } while ($this->linkRepository->findByShortCode($code) !== null);

        return $code;
    }

    private function normalizeExpiry(string $expiresAt): ?string
    {
        if ($expiresAt === '') {
            return null;
        }

        $timestamp = strtotime($expiresAt);
        if ($timestamp === false) {
            throw new InvalidArgumentException('过期时间格式不合法。');
        }

        return date(DATE_ATOM, $timestamp);
    }

    private function findOwnedLink(string $id, array $operator): ?array
    {
        $link = $this->linkRepository->findById($id);
        if ($link === null) {
            return null;
        }

        if (($operator['role'] ?? 'user') !== 'admin' && ($link['user_id'] ?? null) !== ($operator['id'] ?? null)) {
            return null;
        }

        return $link;
    }
}

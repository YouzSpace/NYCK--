<?php

declare(strict_types=1);

namespace ShortUrl\Services;

use InvalidArgumentException;
use ShortUrl\Repositories\LinkRepository;

class StatsService
{
    public function __construct(private readonly LinkRepository $linkRepository)
    {
    }

    public function increasePv(string $linkId): array
    {
        $link = $this->linkRepository->findById($linkId);
        if ($link === null) {
            throw new InvalidArgumentException('短链不存在。');
        }

        $pv = (int) ($link['pv'] ?? 0) + 1;

        return $this->linkRepository->update($linkId, [
            'pv' => $pv,
            'updated_at' => date(DATE_ATOM),
        ]) ?? $link;
    }

    public function getLinkPv(string $linkId): int
    {
        $link = $this->linkRepository->findById($linkId);

        return (int) ($link['pv'] ?? 0);
    }

    public function getDashboardStats(array $operator): array
    {
        $items = $this->linkRepository->all();

        if (($operator['role'] ?? 'user') !== 'admin') {
            $items = array_values(array_filter(
                $items,
                static fn (array $link): bool => ($link['user_id'] ?? null) === ($operator['id'] ?? null)
            ));
        }

        $totalLinks = count($items);
        $totalPv = array_sum(array_map(
            static fn (array $link): int => (int) ($link['pv'] ?? 0),
            $items
        ));
        $recentLinks = count(array_filter(
            $items,
            static fn (array $link): bool => strtotime((string) ($link['created_at'] ?? '')) >= strtotime('-7 days')
        ));

        return [
            'total_links' => $totalLinks,
            'total_pv' => $totalPv,
            'recent_links' => $recentLinks,
        ];
    }
}

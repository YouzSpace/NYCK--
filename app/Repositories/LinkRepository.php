<?php

declare(strict_types=1);

namespace ShortUrl\Repositories;

class LinkRepository
{
    public function __construct(
        private readonly JsonRepository $jsonRepository,
        private readonly string $filePath,
        private readonly array $defaultData = ['items' => []]
    ) {
    }

    public function all(): array
    {
        $data = $this->jsonRepository->readJson($this->filePath, $this->defaultData);

        return $data['items'] ?? [];
    }

    public function findById(string $id): ?array
    {
        foreach ($this->all() as $link) {
            if (($link['id'] ?? null) === $id) {
                return $link;
            }
        }

        return null;
    }

    public function findByShortCode(string $code): ?array
    {
        foreach ($this->all() as $link) {
            if (($link['code'] ?? null) === $code) {
                return $link;
            }
        }

        return null;
    }

    public function create(array $link): array
    {
        $items = $this->all();
        $items[] = $link;
        $this->persist($items);

        return $link;
    }

    public function update(string $id, array $payload): ?array
    {
        $items = $this->all();

        foreach ($items as $index => $link) {
            if (($link['id'] ?? null) !== $id) {
                continue;
            }

            $items[$index] = array_merge($link, $payload);
            $this->persist($items);

            return $items[$index];
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $items = array_values(array_filter(
            $this->all(),
            static fn (array $link): bool => ($link['id'] ?? null) !== $id
        ));

        $this->persist($items);

        return true;
    }

    private function persist(array $items): void
    {
        $this->jsonRepository->writeJson($this->filePath, ['items' => array_values($items)]);
    }
}

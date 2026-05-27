<?php

declare(strict_types=1);

namespace ShortUrl\Repositories;

class UserRepository
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
        foreach ($this->all() as $user) {
            if (($user['id'] ?? null) === $id) {
                return $user;
            }
        }

        return null;
    }

    public function findByUsername(string $username): ?array
    {
        foreach ($this->all() as $user) {
            if (($user['username'] ?? null) === $username) {
                return $user;
            }
        }

        return null;
    }

    public function create(array $user): array
    {
        $items = $this->all();
        $items[] = $user;
        $this->persist($items);

        return $user;
    }

    public function update(string $id, array $payload): ?array
    {
        $items = $this->all();

        foreach ($items as $index => $user) {
            if (($user['id'] ?? null) !== $id) {
                continue;
            }

            $items[$index] = array_merge($user, $payload);
            $this->persist($items);

            return $items[$index];
        }

        return null;
    }

    public function disable(string $id): ?array
    {
        return $this->update($id, ['status' => 'disabled']);
    }

    private function persist(array $items): void
    {
        $this->jsonRepository->writeJson($this->filePath, ['items' => array_values($items)]);
    }
}

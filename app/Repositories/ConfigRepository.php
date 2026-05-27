<?php

declare(strict_types=1);

namespace ShortUrl\Repositories;

class ConfigRepository
{
    public function __construct(
        private readonly JsonRepository $jsonRepository,
        private readonly string $filePath,
        private readonly array $defaultData = []
    ) {
    }

    public function all(): array
    {
        return $this->jsonRepository->readJson($this->filePath, $this->defaultData);
    }

    public function save(array $config): void
    {
        $this->jsonRepository->writeJson($this->filePath, $config);
    }
}

<?php

declare(strict_types=1);

$sourceAssetsDirectory = __DIR__ . '/assets';
$publicAssetsDirectory = __DIR__ . '/public/assets';

if (!is_dir($sourceAssetsDirectory)) {
    return;
}

if (!is_dir($publicAssetsDirectory) && !mkdir($publicAssetsDirectory, 0777, true) && !is_dir($publicAssetsDirectory)) {
    throw new RuntimeException(sprintf('Unable to create public assets directory: %s', $publicAssetsDirectory));
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sourceAssetsDirectory, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $item) {
    $sourcePath = $item->getPathname();
    $relativePath = substr($sourcePath, strlen($sourceAssetsDirectory) + 1);
    $targetPath = $publicAssetsDirectory . DIRECTORY_SEPARATOR . $relativePath;

    if ($item->isDir()) {
        if (!is_dir($targetPath) && !mkdir($targetPath, 0777, true) && !is_dir($targetPath)) {
            throw new RuntimeException(sprintf('Unable to create assets subdirectory: %s', $targetPath));
        }

        continue;
    }

    $targetDirectory = dirname($targetPath);

    if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0777, true) && !is_dir($targetDirectory)) {
        throw new RuntimeException(sprintf('Unable to create assets target directory: %s', $targetDirectory));
    }

    if (!is_file($targetPath) || filemtime($sourcePath) !== filemtime($targetPath)) {
        if (!copy($sourcePath, $targetPath)) {
            throw new RuntimeException(sprintf('Unable to copy asset from %s to %s', $sourcePath, $targetPath));
        }

        touch($targetPath, (int) filemtime($sourcePath));
    }
}

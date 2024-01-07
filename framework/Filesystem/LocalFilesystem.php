<?php

namespace Framework\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

class LocalFilesystem extends FilesystemDriver
{

    /**
     * @throws \Exception
     */
    public function initializeFilesystem(array $config): Filesystem
    {
        $this->createLocalPath($config);
        $adapter = new LocalFilesystemAdapter($config['path']);
        return $this->filesystem = new Filesystem($adapter);
    }

    /**
     * @throws \Exception
     */
    private function createLocalPath(array $config): void
    {
        $path = $config['path'] ?? null;

        if (!$path)
            throw new \Exception('No Path provided for local filesystem');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

    }
}
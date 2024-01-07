<?php

namespace Framework\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;

abstract class FilesystemDriver
{
    protected Filesystem $filesystem;

    public function __construct(array $config)
    {
        $this->filesystem = $this->initializeFilesystem($config);
    }

    abstract public function initializeFilesystem(array $config): Filesystem;

    /**
     * @throws FilesystemException
     */
    public function list(string $path, bool $recursive = false): iterable
    {
        return $this->filesystem->listContents($path, $recursive);
    }

    /**
     * @throws FilesystemException
     */
    public function exists(string $path): bool
    {
        return $this->filesystem->fileExists($path);
    }

    /**
     * @throws FilesystemException
     */
    public function get(string $path): string
    {
        return $this->filesystem->read($path);
    }

    /**
     * @throws FilesystemException
     */
    public function put(string $path, mixed $value): static
    {
        $this->filesystem->write($path, $value);
        return $this;
    }

    /**
     * @throws FilesystemException
     */
    public function delete(string $path): static
    {
        $this->filesystem->delete($path);
        return $this;
    }
}
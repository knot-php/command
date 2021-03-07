<?php
declare(strict_types=1);

namespace KnotPhp\Command\Test;

use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\FileSystem\AbstractFileSystem;
use KnotLib\Kernel\FileSystem\Dir;

final class TestFileSystem extends AbstractFileSystem implements FileSystemInterface
{
    /** @var array */
    private $dir_map;

    public function __construct(string $base_dir)
    {
        $this->dir_map = [
            Dir::DATA      => $base_dir . '/data',
            Dir::COMMAND   => $base_dir . '/command',
            Dir::CACHE     => $base_dir . '/cache',
            Dir::CONFIG    => $base_dir . '/config',
            Dir::LOGS      => $base_dir . '/logs',
            Dir::INCLUDE   => $base_dir . '/include',
            Dir::TEMPLATE   => dirname(__DIR__) . '/template',
        ];
    }

    public function directoryExists(int $dir): bool
    {
        return isset($this->dir_map);
    }

    public function getDirectory(int $dir): string
    {
        return $this->dir_map[$dir] ?? parent::getDirectory($dir);
    }
}
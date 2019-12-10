<?php
declare(strict_types=1);

namespace KnotPhp\Command\Demo;

use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\FileSystem\AbstractFileSystem;

class DemoFileSystem extends AbstractFileSystem implements FileSystemInterface
{
    /** @var array */
    private $dir_map;
    
    /**
     * DemoFileSystem constructor.
     *
     * @param string $base_dir
     */
    public function __construct(string $base_dir)
    {
        $this->dir_map = [
            Dir::COMMAND => $base_dir . '/command',
            Dir::TEMPLATE => dirname(dirname(__DIR__)) . '/template',
            Dir::DATA => $base_dir . '/data',
            Dir::CACHE => $base_dir . '/cache',
        ];
    }

    public function directoryExists(int $dir): bool
    {
        return isset($this->dir_map[$dir]);
    }


    /**
     * Get directory path
     *
     * @param int $dir
     *
     * @return string
     *
     * @throws
     */
    public function getDirectory(int $dir) : string
    {
        return $this->dir_map[$dir] ?? parent::getDirectory($dir);
    }
}
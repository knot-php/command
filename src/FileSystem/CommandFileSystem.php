<?php
declare(strict_types=1);

namespace KnotPhp\Command\FileSystem;

use KnotLib\Kernel\FileSystem\FileSystemFactoryInterface;
use KnotPhp\Command\Env\EnvKey;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\FileSystem\AbstractFileSystem;
use KnotLib\Kernel\NullObject\NullFileSystem;
use KnotPhp\Command\Exception\ClassImplementationException;
use KnotPhp\Command\Exception\ClassNotFoundException;

class CommandFileSystem extends AbstractFileSystem implements FileSystemInterface
{
    const NAMESPACE_SEPARATOR = '\\';

    /** @var array */
    private $dir_map;

    /**
     * CommandFileSystem constructor.
     *
     * @throws
     */
    public function __construct()
    {
        $base_dir = dirname(__DIR__, 2);

        $this->dir_map = [
            Dir::CONFIG   => $base_dir . '/config',
            Dir::TEMPLATE => $base_dir . '/template',
            Dir::CACHE    => $base_dir . '/var/cache',
            Dir::LOGS     => $base_dir . '/var/logs',
            Dir::COMMAND  => $base_dir . '/var/command',
            Dir::DATA     => $base_dir . '/var/data',
        ];
    }

    /**
     * Returns runtime file system
     *
     * @return FileSystemInterface
     *
     * @throws ClassNotFoundException
     * @throws ClassImplementationException
     */
    protected function getRuntimeFileSystem() : FileSystemInterface
    {
        $runtime_fs_factory_class = getenv(EnvKey::COMMAND_FILESYSTEM_FACTORY);

        if (!$runtime_fs_factory_class){
            return new NullFileSystem();
        }

        $runtime_fs_factory_class = str_replace('.', self::NAMESPACE_SEPARATOR, $runtime_fs_factory_class);

        // confirm class is loaded
        if (!class_exists($runtime_fs_factory_class)){
            $msg = sprintf("Class(%s) not found defined at env value: %s", $runtime_fs_factory_class, EnvKey::COMMAND_FILESYSTEM_FACTORY);
            throw new ClassNotFoundException($msg);
        }

        // confirm factory class implements FileSystemFactoryInterface
        if (!in_array(FileSystemFactoryInterface::class, class_implements($runtime_fs_factory_class))){
            throw new ClassImplementationException($runtime_fs_factory_class, FileSystemFactoryInterface::class);
        }

        return forward_static_call([$runtime_fs_factory_class, 'createFileSystem']);
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
        $runtime_fs = $this->getRuntimeFileSystem();

        if ($runtime_fs->directoryExists($dir)){
            return $runtime_fs->getDirectory($dir);
        }

        return $this->dir_map[$dir] ?? parent::getDirectory($dir);
    }
}
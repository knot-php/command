<?php
declare(strict_types=1);

namespace KnotPhp\Command\FileSystem;

use KnotLib\Kernel\FileSystem\FileSystemFactoryInterface;
use KnotLib\Kernel\FileSystem\FileSystemInterface;

final class CommandFileSystemFactory implements FileSystemFactoryInterface
{
    public static function createFileSystem(): FileSystemInterface
    {
        return new CommandFileSystem();
    }

}
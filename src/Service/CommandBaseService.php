<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use KnotLib\Kernel\FileSystem\FileSystemInterface;

class CommandBaseService
{
    /** @var FileSystemInterface */
    private $fs;

    /**
     * CommandBaseService constructor.
     *
     * @param FileSystemInterface $fs
     */
    public function __construct(FileSystemInterface $fs)
    {
        $this->fs = $fs;
    }

    /**
     * @return FileSystemInterface
     */
    public function getFileSystem() : FileSystemInterface
    {
        return $this->fs;
    }
}
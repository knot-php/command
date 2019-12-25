<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

use Throwable;

use Stk2k\Util\Util;

use KnotPhp\Command\Env\EnvKey;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\NullObject\NullFileSystem;

use KnotLib\Kernel\Di\DiContainerInterface;
use KnotLib\Service\Util\DiServiceTrait;
use KnotLib\Service\FileSystemService;
use KnotLib\Service\LoggerService;
use KnotLib\Service\ValidationService;

abstract class AbstractCommand implements CommandInterface
{
    const NAMESPACE_SEPARATOR = '\\';

    use DiServiceTrait {
        getFileSystemService as protected traitGetFileSystemService;
        getLoggerService as protected traitGetLoggerService;
        getValidationService as protected traitGetValidationService;
    }

    /** @var DiContainerInterface */
    private $di;

    /**
     * {@inheritDoc}
     */
    public function __construct(DiContainerInterface $di)
    {
        $this->di = $di;
    }

    /**
     * Returns DI conitaner
     *
     * @return DiContainerInterface
     */
    protected function getContainer() : DiContainerInterface
    {
        return $this->di;
    }

    /**
     * {@inheritDoc}
     */
    public function handleException(Throwable $e, ConsoleIOInterface $io): bool
    {
        Util::dumpException($e, function($line) use($io){
            $io->output($line);
        });

        return true;
    }

    /**
     * Returns runtime file system
     *
     * @return FileSystemInterface
     */
    protected function getRuntimeFileSystem() : FileSystemInterface
    {
        $runtime_fs_factory_class = getenv(EnvKey::COMMAND_FILESYSTEM_FACTORY);

        if (!$runtime_fs_factory_class){
            return new NullFileSystem();
        }

        $runtime_fs_factory_class = str_replace('.', self::NAMESPACE_SEPARATOR, $runtime_fs_factory_class);

        return forward_static_call([$runtime_fs_factory_class, 'createFileSystem']);
    }


    /**
     * Get file system service defined in DI container
     *
     * @return FileSystemService
     *
     * @throws
     */
    protected function getFileSystemService() : FileSystemService
    {
        return $this->traitGetFileSystemService($this->di);
    }

    /**
     * Get logger service defined in DI container
     *
     * @return LoggerService
     *
     * @throws
     */
    protected function getLoggerService() : LoggerService
    {
        return $this->traitGetLoggerService($this->di);
    }

    /**
     * Get validation service defined in DI container
     *
     * @return ValidationService
     *
     * @throws
     */
    protected function getValidationService() : ValidationService
    {
        return $this->traitGetValidationService($this->di);
    }
}
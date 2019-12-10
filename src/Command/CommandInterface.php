<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

use Throwable;

use KnotLib\Kernel\Di\DiContainerInterface;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotPhp\Command\Exception\CommandExecutionException;

interface CommandInterface
{
    /**
     * CommandInterface constructor.
     *
     * @param DiContainerInterface $di
     */
    public function __construct(DiContainerInterface $di);

    /**
     * Returns command id
     *
     * @return string
     */
    public static function getCommandId() : string;

    /**
     * Returns command descriptor
     *
     * @return CommandDescriptor
     */
    public static function getDescriptor() : CommandDescriptor;

    /**
     * Returns required modules by command
     *
     * @return array          list of class names(FQCN)
     */
    public function getRequiredModules() : array;

    /**
     * Returns file system
     *
     * @return FileSystemInterface|null
     */
    //public function getFileSystem();

    /**
     * Execute command
     *
     * @param array $args
     * @param ConsoleIOInterface $io
     *
     * @return int
     *
     * @throws CommandExecutionException
     */
    public function execute(array $args, ConsoleIOInterface $io) : int;

    /**
     * Handle exception while occured in executing the command
     *
     * @param Throwable $e
     * @param ConsoleIOInterface $io
     *
     * @return bool
     */
    public function handleException(Throwable $e, ConsoleIOInterface $io) : bool;
}
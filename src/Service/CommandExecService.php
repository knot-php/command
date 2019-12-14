<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use Throwable;

use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Di\DiContainerInterface;
use KnotPhp\Command\Exception\CommandExecutionException;
use KnotPhp\Command\Command\CommandInterface;
use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\Command\Exception\ClassImplementationException;
use KnotPhp\Command\Exception\ClassNotFoundException;
use KnotPhp\Command\Exception\CommandNotFoundException;

final class CommandExecService extends CommandBaseService
{
    /** @var ApplicationInterface */
    private $app;

    /** @var CommandDbFileService */
    private $command_db;

    /** @var AliasDbFileService */
    private $alias_db;

    /** @var ConsoleIOInterface */
    private $io;

    /**
     * CommandExecService constructor.
     *
     * @param FileSystemInterface $fs
     * @param ApplicationInterface $app
     * @param CommandDbFileService $command_db
     * @param AliasDbFileService $alias_db
     * @param ConsoleIOInterface $io
     */
    public function __construct(
        FileSystemInterface $fs,
        ApplicationInterface $app,
        CommandDbFileService $command_db,
        AliasDbFileService $alias_db,
        ConsoleIOInterface $io)
    {
        parent::__construct($fs);

        $this->app = $app;
        $this->command_db = $command_db;
        $this->alias_db = $alias_db;
        $this->io = $io;
    }

    /**
     * Execute command
     *
     * @param DiContainerInterface $di
     * @param string $command_id
     * @param int $skip_args
     *
     * @return int
     *
     * @throws CommandNotFoundException
     * @throws ClassNotFoundException
     * @throws ClassImplementationException
     * @throws CommandExecutionException
     */
    public function executeCommand(DiContainerInterface $di, string $command_id, int $skip_args) : int
    {
        $this->command_db->load();
        $this->alias_db->load();

        // if alias is specified, expand it
        if ($this->alias_db->isAlias($command_id)){
            $command_id = $this->alias_db->getCommandId($command_id);
        }

        // get descriptor from DB
        $descriptor = $this->command_db->getDesciptor($command_id);

        if (!$descriptor){
            throw new CommandNotFoundException($command_id);
        }

        // create command object
        $class_name = $descriptor->getClassName();
        $ordered_args = $descriptor->getOrderdArgs();
        $named_args = $descriptor->getNamedArgs();

        if (!class_exists($class_name)){
            throw new ClassNotFoundException($class_name);
        }

        if (!in_array(CommandInterface::class, class_implements($class_name))){
            throw new ClassImplementationException($class_name, CommandInterface::class);
        }

        /** @var CommandInterface $command_obj */
        $command_obj = new $class_name($di);

        // install modules required by command
        $modules_required = $command_obj->getRequiredModules();

        $this->app->installModules($modules_required);

        // execute command
        $ret = -1;
        try{
            $combined_args = $this->getArgs($ordered_args, $named_args, $skip_args);

            $ret = $command_obj->execute($combined_args, $this->io);
        }
        catch(Throwable $e)
        {
            if (!$command_obj->handleException($e, $this->io)){
                throw new CommandExecutionException($command_id, $e->getMessage(), 0, $e);
            }
        }

        $this->io->output(sprintf("Command(%s) finished with exit code %d", $command_id, $ret));

        return $ret;
    }

    /**
     * @param array $ordered_args
     * @param array $named_args
     * @param int $skip_args
     *
     * @return array
     * @noinspection PhpUnusedParameterInspection
     */
    private function getArgs(array $ordered_args, array $named_args, int $skip_args) : array
    {
        $request = $this->app->request();
        $seq_params = array_filter($request->getServerParams(), function($value, $key){
            return is_int($key);
        }, ARRAY_FILTER_USE_BOTH);
        $named_params = array_filter($request->getServerParams(), function($value, $key){
            return is_string($key);
        }, ARRAY_FILTER_USE_BOTH);

        $args = [];

        foreach($ordered_args as $idx => $key){
            if (isset($seq_params[$skip_args + $idx])){
                $args[$key] = $seq_params[$skip_args + $idx];
            }
        }

        foreach($named_args as $spec => $key){
            if (isset($named_params[$spec])){
                $args[$key] = $named_params[$spec];
            }
        }

        return $args;
    }
}
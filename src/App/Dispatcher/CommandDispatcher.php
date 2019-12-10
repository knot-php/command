<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Dispatcher;

use Throwable;

use KnotLib\Console\Router\ShellDispatcherInterface;
use KnotLib\Console\Router\ShellRouter;
use KnotLib\Kernel\Di\DiContainerInterface;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Service\LoggerService;

use KnotPhp\Command\App\Responder\BaseCommandResponder;
use KnotPhp\Command\App\Responder\SystemCommandResponder;
use KnotPhp\Command\App\Responder\ErrorCommandResponder;
use KnotPhp\Command\Base\Config\AppConfig;
use KnotPhp\Command\Exception\RouteNotFoundException;
use KnotPhp\Command\Service\DI;
use KnotPhp\Command\App\Responder\CommandCommandResponder;
use KnotPhp\Command\Service\AliasDbFileService;
use KnotPhp\Command\Service\CommandDbFileService;

class CommandDispatcher implements ShellDispatcherInterface
{
    /** @var AppConfig */
    private $config;

    /** @var LoggerService */
    private $logger;

    /** @var DiContainerInterface */
    private $di;

    /** @var FileSystemInterface */
    private $fs;

    public function __construct(AppConfig $config, LoggerService $logger, DiContainerInterface $di, FileSystemInterface $fs)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->di = $di;
        $this->fs = $fs;
    }

    /**
     * Get logger
     *
     * @return LoggerService
     */
    public function getLogger() : LoggerService
    {
        return $this->logger;
    }

    /**
     * Dispatch event
     *
     * @param string $path
     * @param array $vars
     * @param string $route_name
     *
     * @return bool
     *
     * @throws
     */
    public function dispatch(string $path, array $vars, string $route_name) : bool
    {
        global $argv;

        $this->logger->debug('dispatched: ' . $route_name);
        $this->logger->debug('argv: ' . print_r($argv, true));

        //$repos   = $this->di['services.repository'];

        try{
            switch($route_name){
                case ShellRouter::ROUTE_NOT_FOUND:
                    // find command and execute
                    $command_db = $this->di[DI::SERVICE_COMMAND_DB_FILE];
                    $alias_db   = $this->di[DI::SERVICE_ALIAS_DB_FILE];
                    $autoload   = $this->di[DI::SERVICE_COMMAND_AUTOLOAD];
                    $exec       = $this->di[DI::SERVICE_COMMAND_EXEC];
                    $io         = $this->di[DI::COMPONENT_CONSOLE_IO];

                    /** @var AliasDbFileService $alias_db */
                    $alias_db->load();
                    if ($alias_db->isAlias($path)){
                        $path = $alias_db->getCommandId($path);
                    }

                    /** @var CommandDbFileService $command_db */
                    $command_db->load();
                    if ($command_db->getDesciptor($path)){
                        (new CommandCommandResponder($this->di, $this->logger, $this->fs, $io))
                            ->execute($autoload, $exec, $path, 2);
                    }
                    else{
                        $command_db_file = $command_db->getCommandDbFile();
                        // command not found(error)
                        (new ErrorCommandResponder($this->logger, $io))->notFound($path, $command_db_file);
                    }
                    break;

                // show system version
                case 'system.version':
                    $system   = $this->di[DI::SERVICE_SYSTEM];
                    (new SystemCommandResponder($this->logger))
                        ->version($system);
                    return true;

                // make command
                case 'command.make':
                    $provider_class = $argv[2] ?? '';
                    $desc_s     = $this->di[DI::SERVICE_COMMAND_DESCRIPTOR];
                    $io         = $this->di[DI::COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($this->di, $this->logger, $this->fs, $io))
                        ->makeCommand($desc_s, $provider_class);
                    return true;

                // install command
                case 'command.install':
                    $comand_id = $argv[2] ?? '';
                    $desc_s     = $this->di[DI::SERVICE_COMMAND_DESCRIPTOR];
                    $command_db = $this->di[DI::SERVICE_COMMAND_DB_FILE];
                    $alias_db   = $this->di[DI::SERVICE_ALIAS_DB_FILE];
                    $io         = $this->di[DI::COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($this->di, $this->logger, $this->fs, $io))
                        ->installCommand($desc_s, $command_db, $alias_db, $comand_id);
                    return true;

                // list command
                case 'command.list':
                    $desc_s     = $this->di[DI::SERVICE_COMMAND_DESCRIPTOR];
                    $io         = $this->di[DI::COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($this->di, $this->logger, $this->fs, $io))
                        ->listCommand($desc_s);
                    return true;

                // help command
                case 'command.help':
                    $comand_id = $argv[2] ?? '';
                    $desc_s     = $this->di[DI::SERVICE_COMMAND_DESCRIPTOR];
                    $io         = $this->di[DI::COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($this->di, $this->logger, $this->fs, $io))
                        ->helpCommand($desc_s, $comand_id);
                    return true;

                // autoload command
                case 'command.autoload':
                    $autoload   = $this->di[DI::SERVICE_COMMAND_AUTOLOAD];
                    $io         = $this->di[DI::COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($this->di, $this->logger, $this->fs, $io))
                        ->generateAutoloadCache($autoload);
                    return true;

                default:
                    throw new RouteNotFoundException($route_name);
            }
        }
        catch(Throwable $e)
        {
            $this->logger->logException($e);

            (new BaseCommandResponder($this->logger))->failure( $e->getMessage() );
        }
        return true;
    }
}
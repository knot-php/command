<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Dispatcher;

use Throwable;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Console\Router\ShellDispatcherInterface;
use KnotLib\Console\Router\ShellRouter;
use KnotLib\Service\LoggerService;
use KnotLib\Service\Util\DiServiceTrait;
use KnotPhp\Command\App\Responder\BaseCommandResponder;
use KnotPhp\Command\App\Responder\SystemCommandResponder;
use KnotPhp\Command\App\Responder\ErrorCommandResponder;
use KnotPhp\Command\Exception\RouteNotFoundException;
use KnotPhp\Command\Service\DI;
use KnotPhp\Command\App\Responder\CommandCommandResponder;
use KnotPhp\Command\Service\AliasDbFileService;
use KnotPhp\Command\Service\CommandDbFileService;

class CommandDispatcher implements ShellDispatcherInterface
{
    use DiServiceTrait;
    
    /** @var ApplicationInterface */
    private $app;


    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
     * Get logger
     *
     * @return LoggerService
     * 
     * @throws 
     */
    public function getLogger() : LoggerService
    {
        return $this->getLoggerService($this->app->di());
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
    public function dispatch(string $path, array $vars, string $route_name) : void
    {
        global $argv;

        $logger = $this->getLogger();
        
        $logger->debug('dispatched: ' . $route_name);
        $logger->debug('argv: ' . print_r($argv, true));
        
        $container = $this->app->di();
        
        $fs = $this->app->filesystem();

        //$repos   = $container['services.repository'];

        try{
            switch($route_name){
                case ShellRouter::ROUTE_NOT_FOUND:
                    // find command and execute
                    $command_db = $container[DI::URI_SERVICE_COMMAND_DB_FILE];
                    $alias_db   = $container[DI::URI_SERVICE_ALIAS_DB_FILE];
                    $autoload   = $container[DI::URI_SERVICE_COMMAND_AUTOLOAD];
                    $exec       = $container[DI::URI_SERVICE_COMMAND_EXEC];
                    $io         = $container[DI::URI_COMPONENT_CONSOLE_IO];

                    /** @var AliasDbFileService $alias_db */
                    $alias_db->load();
                    if ($alias_db->isAlias($path)){
                        $path = $alias_db->getCommandId($path);
                    }

                    /** @var CommandDbFileService $command_db */
                    $command_db->load();
                    if ($command_db->getDesciptor($path)){
                        (new CommandCommandResponder($container, $logger, $fs, $io))
                            ->execute($autoload, $exec, $path, 2);
                    }
                    else{
                        $command_db_file = $command_db->getCommandDbFile();
                        // command not found(error)
                        (new ErrorCommandResponder($logger, $io))->notFound($path, $command_db_file);
                    }
                    break;

                // show system version
                case 'system.version':
                    $system   = $container[DI::URI_SERVICE_SYSTEM];
                    (new SystemCommandResponder($logger))
                        ->version($system);
                    break;

                // make command
                case 'command.make':
                    $provider_class = $argv[2] ?? '';
                    $desc_s     = $container[DI::URI_SERVICE_COMMAND_DESCRIPTOR];
                    $io         = $container[DI::URI_COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($container, $logger, $fs, $io))
                        ->makeCommand($desc_s, $provider_class);
                    break;

                // install command
                case 'command.install':
                    $comand_id = $argv[2] ?? '';
                    $desc_s     = $container[DI::URI_SERVICE_COMMAND_DESCRIPTOR];
                    $command_db = $container[DI::URI_SERVICE_COMMAND_DB_FILE];
                    $alias_db   = $container[DI::URI_SERVICE_ALIAS_DB_FILE];
                    $io         = $container[DI::URI_COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($container, $logger, $fs, $io))
                        ->installCommand($desc_s, $command_db, $alias_db, $comand_id);
                    break;

                // list command
                case 'command.list':
                    $desc_s     = $container[DI::URI_SERVICE_COMMAND_DESCRIPTOR];
                    $io         = $container[DI::URI_COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($container, $logger, $fs, $io))
                        ->listCommand($desc_s);
                    break;

                // help command
                case 'command.help':
                    $comand_id = $argv[2] ?? '';
                    $desc_s     = $container[DI::URI_SERVICE_COMMAND_DESCRIPTOR];
                    $io         = $container[DI::URI_COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($container, $logger, $fs, $io))
                        ->helpCommand($desc_s, $comand_id);
                    break;

                // autoload command
                case 'command.autoload':
                    $autoload   = $container[DI::URI_SERVICE_COMMAND_AUTOLOAD];
                    $io         = $container[DI::URI_COMPONENT_CONSOLE_IO];
                    (new CommandCommandResponder($container, $logger, $fs, $io))
                        ->generateAutoloadCache($autoload);
                    break;

                default:
                    throw new RouteNotFoundException($route_name);
            }
        }
        catch(Throwable $e)
        {
            $logger->logException($e);

            (new BaseCommandResponder($logger))->failure( $e->getMessage() );
        }
    }
}
<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Module;

use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\Module\Components;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ModuleInterface;

use KnotPhp\Module\KnotConsole\KnotShellRouterModule;

use KnotPhp\Command\App\Dispatcher\CommandDispatcher;
use KnotPhp\Command\Service\DI;

class CommandRouterModule extends KnotShellRouterModule implements ModuleInterface
{
    const ROUTING_RULE = [
        "system:version" => "system.version",
        "command:make" => "command.make",
        "command:install" => "command.install",
        "command:autoload" => "command.autoload",
        "command:list" => "command.list",
        "command:help" => "command.help",
    ];

    /** @var FileSystemInterface */
    private $fs;

    /**
     * Declare dependent on another modules
     *
     * @return array
     */
    public static function requiredModules() : array
    {
        return [
            CommandDiModule::class,
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return Components::MODULE;
    }

    /**
     * Get dispatcher
     *
     * {@inheritDoc}
     */
    public function getDispatcher(ApplicationInterface $app)
    {
        $di = $app->di();

        $logger = $di[DI::SERVICE_LOGGER];
        $app_config = $di['app_config'];

        $this->fs = $app->filesystem();

        return new CommandDispatcher($app_config, $logger, $di, $app->filesystem());
    }

    /**
     * {@inheritDoc}
     */
    public function getRoutingRule(): array
    {
        return self::ROUTING_RULE;
    }
}
<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Module;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ModuleFactoryInterface;
use KnotPhp\Command\App\Dispatcher\CommandDispatcher;
use KnotPhp\Module\KnotConsole\ArrayConfigShellRouterModule;

final class CommandModuleFactory implements ModuleFactoryInterface
{
    const ROUTING_RULE = [
        "system:version" => "system.version",
        "command:make" => "command.make",
        "command:install" => "command.install",
        "command:autoload" => "command.autoload",
        "command:list" => "command.list",
        "command:help" => "command.help",
    ];

    public function createModule(string $module_class, ApplicationInterface $app)
    {
        if ($module_class === ArrayConfigShellRouterModule::class){
            return new ArrayConfigShellRouterModule(new CommandDispatcher($app), self::ROUTING_RULE);
        }
        return null;
    }
}
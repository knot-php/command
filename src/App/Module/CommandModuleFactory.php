<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Module;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ModuleFactoryInterface;
use KnotPhp\Command\App\Dispatcher\CommandDispatcher;
use KnotPhp\Module\KnotConsole\KnotShellRouterModule;

final class CommandModuleFactory implements ModuleFactoryInterface
{
    public function createModule(string $module_class, ApplicationInterface $app)
    {
        if ($module_class === KnotShellRouterModule::class){
            return new KnotShellRouterModule(new CommandDispatcher($app));
        }
        return null;
    }

}
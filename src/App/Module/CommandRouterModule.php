<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Module;

use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Kernel\Module\Components;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ModuleInterface;

use KnotPhp\Module\KnotConsole\KnotShellRouterModule;

use KnotPhp\Command\App\Dispatcher\CommandDispatcher;
use KnotPhp\Command\Service\DI;

class CommandRouterModule extends KnotShellRouterModule implements ModuleInterface
{
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
        $config_file = $this->fs->getFile(Dir::CONFIG, 'route.config.php');
        /** @noinspection PhpIncludeInspection */
        return require($config_file);
    }
}
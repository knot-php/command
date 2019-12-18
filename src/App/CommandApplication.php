<?php
declare(strict_types=1);

namespace KnotPhp\Command\App;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Kernel\ApplicationType;
use KnotLib\Module\Application\PluginModuleLoader;
use KnotLib\Module\Application\SimpleApplication;

use KnotPhp\Command\App\Module\CommandModuleFactory;
use KnotPhp\Module\KnotConsole\Package\KnotArrayConfigConsolePackage;
use KnotPhp\Module\KnotDi\KnotDiModule;
use KnotPhp\Module\KnotLogger\KnotLoggerModule;
use KnotPhp\Module\KnotPipeline\KnotPipelineModule;
use KnotPhp\Module\KnotService\KnotServiceModule;
use KnotPhp\Module\Stk2kEventStream\Stk2kEventStreamModule;
use KnotPhp\Command\App\Module\CommandDiModule;

class CommandApplication extends SimpleApplication
{
    /**
     * {@inheritDoc}
     */
    public static function type(): ApplicationType
    {
        return ApplicationType::of(ApplicationType::CLI);
    }

    /**
     * Configure application
     *
     * @throws
     */
    public function configure() : ApplicationInterface
    {
        // install packages
        $this->requirePackage(KnotArrayConfigConsolePackage::class);

        // install modules
        $this->requireModule(Stk2kEventStreamModule::class);
        $this->requireModule(KnotPipelineModule::class);
        $this->requireModule(KnotDiModule::class);
        $this->requireModule(KnotServiceModule::class);
        $this->requireModule(KnotLoggerModule::class);
        $this->requireModule(CommandDiModule::class);

        $this->addModuleFactory(new CommandModuleFactory());

        PluginModuleLoader::loadPluginModules($this);

        return $this;
    }

}
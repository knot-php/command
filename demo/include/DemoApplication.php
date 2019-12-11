<?php
declare(strict_types=1);

namespace KnotPhp\Command\Demo;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Kernel\ApplicationType;
use KnotLib\Module\Application\SimpleApplication;

use KnotPhp\Module\KnotConsole\KnotShellRequestModule;
use KnotPhp\Module\KnotConsole\KnotShellResponseModule;
use KnotPhp\Module\KnotDi\KnotDiModule;
use KnotPhp\Module\KnotPipeline\KnotPipelineModule;
use KnotPhp\Module\KnotService\KnotServiceModule;
use KnotPhp\Module\Stk2kEventStream\Stk2kEventStreamModule;

final class DemoApplication extends SimpleApplication
{
    public static function type(): ApplicationType
    {
        return ApplicationType::of(ApplicationType::CLI);
    }

    /**
     * Configure application
     *
     * @return ApplicationInterface
     */
    public function configure() : ApplicationInterface
    {
        $this->requireModule(Stk2kEventStreamModule::class);
        $this->requireModule(KnotPipelineModule::class);
        $this->requireModule(KnotDiModule::class);
        $this->requireModule(KnotServiceModule::class);
        $this->requireModule(KnotShellRequestModule::class);
        $this->requireModule(KnotShellResponseModule::class);

        return $this;
    }
}
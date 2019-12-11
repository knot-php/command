<?php
declare(strict_types=1);

namespace KnotPhp\Command\Test;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Kernel\ApplicationType;
use KnotLib\Module\Application\SimpleApplication;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\FileSystem\AbstractFileSystem;

use KnotPhp\Module\KnotConsole\Package\KnotConsolePackage;
use KnotPhp\Module\KnotDi\KnotDiModule;
use KnotPhp\Module\KnotLogger\KnotLoggerModule;
use KnotPhp\Module\KnotPipeline\KnotPipelineModule;
use KnotPhp\Module\KnotService\KnotServiceModule;
use KnotPhp\Module\Stk2kEventStream\Stk2kEventStreamModule;

use KnotPhp\Command\App\Module\CommandDiModule;
use KnotPhp\Command\App\Module\CommandRouterModule;

final class TestApplication extends SimpleApplication
{
    public function __construct(/** @noinspection PhpUnusedParameterInspection */ FileSystemInterface $filesystem = null)
    {
        parent::__construct(new class extends AbstractFileSystem implements FileSystemInterface {
            public function getDirectory(int $dir): string
            {
                $map = [
                ];
                return $map[$dir] ?? parent::getDirectory($dir);
            }
        });
    }

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
        $this->requirePackage(KnotConsolePackage::class);

        // install modules
        $this->requireModule(Stk2kEventStreamModule::class);
        $this->requireModule(KnotPipelineModule::class);
        $this->requireModule(KnotDiModule::class);
        $this->requireModule(KnotServiceModule::class);
        $this->requireModule(KnotLoggerModule::class);
        $this->requireModule(CommandRouterModule::class);
        $this->requireModule(CommandDiModule::class);

        return $this;
    }

}
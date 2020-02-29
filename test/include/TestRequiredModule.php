<?php
declare(strict_types=1);

namespace KnotPhp\Command\Test;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\AbstractModule;
use KnotLib\Kernel\Module\ComponentTypes;
use KnotLib\Kernel\Module\ModuleInterface;

final class TestRequiredModule extends AbstractModule implements ModuleInterface
{
    public static function declareComponentType(): string
    {
        return ComponentTypes::APPLICATION;
    }

    public function install(ApplicationInterface $app)
    {
    }
}
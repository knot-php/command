<?php
declare(strict_types=1);

namespace KnotPhp\Command\Test;

use KnotLib\Kernel\Di\DiContainerInterface;
use KnotLib\Di\Container;

use KnotPhp\Module\KnotDi\Adapter\KnotDiContainerAdapter;

final class TestDiContainer extends KnotDiContainerAdapter implements DiContainerInterface
{
    public function __construct()
    {
        parent::__construct(new Container());
    }
}
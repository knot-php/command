<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use KnotLib\Exception\KnotPhpExceptionInterface;
use KnotLib\Exception\Runtime\RuntimeExceptionInterface;

interface CommandExceptionInterface extends KnotPhpExceptionInterface, RuntimeExceptionInterface
{
}
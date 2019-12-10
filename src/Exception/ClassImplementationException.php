<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use Throwable;

class ClassImplementationException extends CommandException
{
    /**
     * ClassNotFoundException constructor.
     *
     * @param string $class_name
     * @param string $interface_name
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( string $class_name, string $interface_name, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "Class($class_name) must implement interface: $interface_name", $code, $prev );
    }
}
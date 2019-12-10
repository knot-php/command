<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use Throwable;

class RouteNotFoundException extends CommandException
{
    /**
     * RouteNotFoundException constructor.
     *
     * @param string $event
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( string $event, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "Target not found for event: $event", $code, $prev );
    }
}
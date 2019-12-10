<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use Throwable;

class CommandNotFoundException extends CommandException
{
    /**
     * CommandNotFoundException constructor.
     *
     * @param string $command_id
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( string $command_id, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "Command not found: $command_id", $code, $prev );
    }
}
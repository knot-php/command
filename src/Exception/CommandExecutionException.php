<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use Throwable;

class CommandExecutionException extends CommandException
{
    /**
     * CommandExecutionException constructor.
     *
     * @param string $command_id
     * @param string $reason
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( string $command_id, string $reason, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "Failed to execute command: $command_id reason: $reason", $code, $prev );
    }
}
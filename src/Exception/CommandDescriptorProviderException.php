<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use Throwable;

class CommandDescriptorProviderException extends CommandException
{
    /**
     * CommandSpecProviderException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( string $message, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct($message, $code, $prev );
    }
}
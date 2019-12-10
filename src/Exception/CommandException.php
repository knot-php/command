<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use Throwable;

use KnotLib\Exception\KnotPhpException;

class CommandException extends KnotPhpException implements CommandExceptionInterface
{
    /**
     * CommandException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( string $message, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct($message, $code, $prev);
    }
}


<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use Throwable;

class DescriptorFileFormatException extends CommandException
{
    /**
     * DescriptorFileFormatException constructor.
     *
     * @param string $spec_file
     * @param string $reason
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( string $spec_file, string $reason, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "Invalid command descriptor file: $spec_file($reason)", $code, $prev );
    }
}
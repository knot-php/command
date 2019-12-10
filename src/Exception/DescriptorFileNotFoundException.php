<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use Throwable;

class DescriptorFileNotFoundException extends CommandException
{
    /**
     * DescriptorFileNotFoundException constructor.
     *
     * @param string $spec_file
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( string $spec_file, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "Command descriptor file not found: $spec_file", $code, $prev );
    }
}
<?php
declare(strict_types=1);

namespace KnotPhp\Command\Exception;

use Throwable;

class TemplateFileNotFoundException extends CommandException
{
    /**
     * TemplateFileNotFoundException constructor.
     *
     * @param string $template_file
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( string $template_file, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct( "Template file not found: $template_file", $code, $prev );
    }
}
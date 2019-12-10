<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

interface CommandDescriptorProviderInterface
{
    /**
     * Provide command descriptors
     *
     * @return CommandDescriptor[]
     */
    public static function provide() : array;
}
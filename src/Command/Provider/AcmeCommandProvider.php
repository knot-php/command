<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command\Provider;

use KnotPhp\Command\Command\Acme\ModuleDependencyExplainComand;
use KnotPhp\Command\Command\Acme\PasswordEncryptComand;
use KnotPhp\Command\Command\CommandDescriptorProviderInterface;

final class AcmeCommandProvider implements CommandDescriptorProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public static function provide(): array
    {
        return [
            PasswordEncryptComand::getDescriptor(),
            ModuleDependencyExplainComand::getDescriptor(),
        ];
    }
}
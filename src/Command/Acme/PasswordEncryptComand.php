<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command\Acme;

use KnotPhp\Command\Command\AbstractCommand;
use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Command\CommandInterface;
use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\Command\Exception\CommandExecutionException;

use KnotPhp\Command\Command\DescriptorKey as Key;

final class PasswordEncryptComand extends AbstractCommand implements CommandInterface
{
    /**
     * Returns command id
     *
     * @return string
     */
    public static function getCommandId() : string
    {
        return 'password:encrypt';
    }

    /**
     * Returns command descriptor
     *
     * @return CommandDescriptor
     */
    public static function getDescriptor() : CommandDescriptor
    {
        return new CommandDescriptor([
            Key::COMMAND_ID => self::getCommandId(),
            Key::ALIASES => [
                'pass:enc',
            ],
            Key::CLASS_ROOT => dirname(dirname(__DIR__)),
            Key::CLASS_NAME => str_replace('\\', '.', self::class),
            Key::CLASS_BASE => 'KnotPhp.Command',
            Key::ORDERED_ARGS => ['password'],
            Key::NAMED_ARGS => [],
            Key::COMMAND_HELP => [
                'calgamo password:encrypt password',
                'calgamo pass:enc password',
            ]
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $args, ConsoleIOInterface $io): int
    {
        $password = $args['password'] ?? '';

        if (empty($password)){
            throw new CommandExecutionException($this->getCommandId(), 'Empty passowrd is specified.');
        }

        $encrypted = password_hash($password, PASSWORD_DEFAULT);

        $io->output('input: ' . $password);
        $io->output('encrypted: ' . $encrypted);

        return 0;
    }
}
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
            Key::REQUIRED => [],
            Key::ORDERED_ARGS => ['password'],
            Key::NAMED_ARGS => [],
            Key::COMMAND_HELP => [
                'knot password:encrypt password',
                'knot pass:enc password',
            ]
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $args, ConsoleIOInterface $io): int
    {
        $io->output('cmd: ' . self::getCommandId())->eol();

        $password = $args['password'] ?? '';

        $io->output('password: ' . $password)->eol();

        if (empty($password)){
            throw new CommandExecutionException($this->getCommandId(), 'Parameter[password] must be specified.');
        }

        $encrypted = password_hash($password, PASSWORD_DEFAULT);

        $io->output('input: ' . $password)->eol();
        $io->output('encrypted: ' . $encrypted)->eol();

        $io->output('OK.')->eol();

        return 0;
    }
}
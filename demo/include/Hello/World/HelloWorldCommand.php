<?php
declare(strict_types=1);

namespace KnotPhp\Command\Demo\Hello\World;

use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\Command\Command\AbstractCommand;

final class HelloWorldCommand extends AbstractCommand
{
    /**
     * Returns command id
     *
     * @return string
     */
    public static function getCommandId() : string
    {
        return 'hello:world';
    }

    /**
     * Returns command descriptor
     *
     * @return CommandDescriptor
     */
    public static function getDescriptor() : CommandDescriptor
    {
        return new CommandDescriptor([
            'command_id' => self::getCommandId(),
            'aliases' => ['h:w'],
            'class_root' => dirname(dirname(__DIR__)),
            'class_name' => self::class,
            'class_base' => 'Demo\\',
            'ordered_args' => ['full_name', 'nickname'],
            'named_args' => ['--verbose' => 'verbose'],
            'command_help' => [
                'hello:world full_name nickname [--verbose true|false]',
                'h:w full_name nickname [--verbose true|false]',
            ]
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $args, ConsoleIOInterface $io): int
    {
        $full_name = $args['full_name'] ?? '';
        $nickname = $args['nickname'] ?? '';
        $verbose = $args['verbose'] ?? false;

        $io->output('Hello, World! Mr.' . $full_name . '(' . $nickname . ')');
        if ($verbose){
            $io->output("I'll be back!");
        }

        return 0;
    }

}
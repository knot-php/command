<?php
declare(strict_types=1);

namespace KnotPhp\Command\Test;

use KnotPhp\Command\Command\AbstractCommand;
use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Command\CommandInterface;
use KnotPhp\Command\Command\ConsoleIOInterface;

final class TestCommand extends AbstractCommand implements CommandInterface
{
    /**
     * Returns command id
     *
     * @return string
     */
    public static function getCommandId() : string
    {
        return 'foo:bar';
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
            'aliases' => ['f:b'],
            'class_root' => __DIR__,
            'class_name' => str_replace('\\', '.', self::class),
            'class_base' => 'Test',
            'required' => [
                TestRequiredModule::class,
            ],
            'ordered_args' => ['name', 'favorite'],
            'named_args' => ['--age' => 'age'],
            'command_help' => [
                'foo:bar name favorite [--age]',
                'f:b name favorite [--age]',
            ]
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $args, ConsoleIOInterface $io): int
    {
        $name = $args['name'] ?? '';
        $favorite = $args['favorite'] ?? '';
        $age = $args['age'] ?? false;

        $io->output('My name is: ' . $name)->eol();
        $io->output('And my favorite is: ' . $favorite)->eol();
        $io->output('I am ' . $age . ' years old.')->eol();

        return 0;
    }
}
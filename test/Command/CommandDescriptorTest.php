<?php
declare(strict_types=1);

namespace KnotPhp\Command\Test;

use PHPUnit\Framework\TestCase;
use KnotPhp\Command\Command\CommandDescriptor;

final class CommandDescriptorTest extends TestCase
{
    public function testGetCommandId()
    {
        $desc = new CommandDescriptor([
            'command_id' => 'foo'
        ]);

        $this->assertEquals('foo', $desc->getCommandId());
    }
    public function testGetAliases()
    {
        $desc = new CommandDescriptor([
            'aliases' => [
                'foo', 'bar', 'tiger' => 'cat'
            ]
        ]);

        $this->assertEquals(['foo', 'bar', 'tiger' => 'cat'], $desc->getAliases());
    }
    public function testGetClassName()
    {
        $desc = new CommandDescriptor([
            'class_name' => 'foo'
        ]);

        $this->assertEquals('foo', $desc->getClassName());
    }
    public function testGetClassRoot()
    {
        $desc = new CommandDescriptor([
            'class_root' => 'foo'
        ]);

        $this->assertEquals('foo', $desc->getClassRoot());
    }
    public function testGetClassBase()
    {
        $desc = new CommandDescriptor([
            'class_base' => 'foo'
        ]);

        $this->assertEquals('foo', $desc->getClassBase());
    }
    public function testGetRequired()
    {
        $desc = new CommandDescriptor([
            'required' => [
                'foo', 'bar',
            ]
        ]);

        $this->assertEquals(['foo', 'bar'], $desc->getRequired());
    }
    public function testGetOrderdArgs()
    {
        $desc = new CommandDescriptor([
            'ordered_args' => [
                'foo', 'bar',
            ]
        ]);

        $this->assertEquals(['foo', 'bar'], $desc->getOrderdArgs());
    }
    public function testGetNamedArgs()
    {
        $desc = new CommandDescriptor([
            'named_args' => [
                'tiger' => 'cat'
            ]
        ]);

        $this->assertEquals(['tiger' => 'cat'], $desc->getNamedArgs());
    }
    public function testGetCommandHelp()
    {
        $desc = new CommandDescriptor([
            'command_help' => [
                'tiger', 'cat'
            ]
        ]);

        $this->assertEquals(['tiger', 'cat'], $desc->getCommandHelp());
    }
    public function testJsonSerialize()
    {
        $desc = new CommandDescriptor([
            'command_id' => 'foo:bar',
            'aliases' => [
                'f:bar', 'f:b',
            ],
            'class_name' => 'Foo.Bar',
            'class_root' => '/path/to/command_root',
            'class_base' => 'Foo',
            'filesystem_factory' => 'Foo.MyFileSystemFactory',
            'ordered_args' => [
                'name', 'favorite',
            ],
            'named_args' => [
                '--age' => 'age'
            ],
            'command_help' => [
                'foo:bar name favorite [--age=10]',
                'f:bar name favorite [--age=10]',
            ]
        ]);

        $expected = <<<JSON
{
    "command_id": "foo:bar",
    "aliases": [
        "f:bar",
        "f:b"
    ],
    "class_root": "/path/to/command_root",
    "class_name": "Foo.Bar",
    "class_base": "Foo",
    "args": {
        "ordered": [
            "name",
            "favorite"
        ],
        "named": {
            "--age": "age"
        }
    },
    "command_help": [
        "foo:bar name favorite [--age=10]",
        "f:bar name favorite [--age=10]"
    ]
}
JSON;


        $this->assertEquals($expected, json_encode($desc, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
    }
}
<?php
declare(strict_types=1);

namespace KnotPhp\Command\Test;

use KnotPhp\Command\Command\DefaultConsoleIO;
use PHPUnit\Framework\TestCase;

use KnotPhp\Command\Command\Acme\PasswordEncryptComand;

final class PasswordEncryptCommandTest extends TestCase
{
    public function testGetDescriptor()
    {
        $desc = PasswordEncryptComand::getDescriptor();

        $class_root = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'src';
        $class_root = str_replace('\\', '\\\\', $class_root);

        $expected = <<<JSON
{
    "command_id": "password:encrypt",
    "aliases": [
        "pass:enc"
    ],
    "class_root": "{$class_root}",
    "class_name": "KnotPhp.Command.Command.Acme.PasswordEncryptComand",
    "class_base": "KnotPhp.Command",
    "args": {
        "ordered": [
            "password"
        ],
        "named": []
    },
    "command_help": [
        "calgamo password:encrypt password",
        "calgamo pass:enc password"
    ]
}
JSON;


        $this->assertEquals($expected, json_encode($desc, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }

    /**
     * @throws
     */
    public function testExecute()
    {
        $di = new TestDiContainer();
        $command = new PasswordEncryptComand($di);

        ob_start();
        $command->execute(['password'=>'foo'], new DefaultConsoleIO());
        $output = ob_get_clean();

        $output = explode(PHP_EOL, $output);

        $regex = '@encrypted: ([\x21-\x7e]+)@';

        $this->assertEquals(1, preg_match($regex, $output[1]));
    }
}
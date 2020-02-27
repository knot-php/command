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
    "required": [],
    "args": {
        "ordered": [
            "password"
        ],
        "named": []
    },
    "command_help": [
        "knot password:encrypt password",
        "knot pass:enc password"
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

        var_dump($output);

        $regex = '@encrypted: ([\x21-\x7e]+)@';

        $this->assertEquals("cmd: password:encrypt", $output[0]);
        $this->assertEquals("password: foo", $output[1]);
        $this->assertEquals("input: foo", $output[2]);
        $this->assertEquals(1, preg_match($regex, $output[3]));
    }
}
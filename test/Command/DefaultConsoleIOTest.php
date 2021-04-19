<?php
declare(strict_types=1);

namespace KnotPhp\Command\Test\Command;

use KnotPhp\Command\Command\DefaultConsoleIO;
use PHPUnit\Framework\TestCase;

final class DefaultConsoleIOTest extends TestCase
{
    public function testOutput()
    {
        $io = new DefaultConsoleIO();

        ob_start();
        $io->output("test");
        $msg = ob_get_clean();

        $this->assertEquals("test", $msg);

        $io = new DefaultConsoleIO();

        ob_start();
        $io->output("foo", "bar");
        $msg = ob_get_clean();

        $this->assertEquals("foobar", $msg);
    }
    public function testEol()
    {
        $io = new DefaultConsoleIO();

        ob_start();
        $io->eol();
        $msg = ob_get_clean();

        $this->assertEquals(PHP_EOL, $msg);

        $io = new DefaultConsoleIO();

        ob_start();
        $io->output("foo")->output("bar")->eol();
        $msg = ob_get_clean();

        $this->assertEquals("foobar" . PHP_EOL, $msg);
    }
}
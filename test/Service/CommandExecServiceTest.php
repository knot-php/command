<?php
declare(strict_types=1);

namespace KnotPhp\Command\Test;

use PHPUnit\Framework\TestCase;

use KnotLib\Console\Request\ShellRequest;
use KnotPhp\Command\FileSystem\CommandFileSystem;
use KnotPhp\Command\Env\EnvKey;
use KnotPhp\Command\Command\DefaultConsoleIO;
use KnotPhp\Command\Service\AliasDbFileService;
use KnotPhp\Command\Service\CommandDbFileService;
use KnotPhp\Command\Service\CommandDescriptorService;
use KnotPhp\Command\Service\CommandExecService;

final class CommandExecServiceTest extends TestCase
{
    /**
     * @throws
     */
    public function setUp()
    {
        parent::setUp();

        $setting = EnvKey::COMMAND_FILESYSTEM_FACTORY . '=' . TestFileSystemFactory::class;
        putenv($setting);

        $fs = new CommandFileSystem();

        $desc_s = new CommandDescriptorService($fs);

        $descriptor_path = $desc_s->generateCommandDescriptor(TestCommand::getDescriptor());

        $test_command_descriptor = $desc_s->readCommandDescriptor($descriptor_path);

        $db_file_s = new CommandDbFileService($fs);
        $alias_db = new AliasDbFileService($fs);

        $db_file_s->setDesciptor($test_command_descriptor->getCommandId(), $test_command_descriptor);

        $db_file_s->save();

        $alias_db->importAlias($db_file_s);
        $alias_db->save();
    }

    /**
     * @throws
     */
    public function testExecuteCommand()
    {
        $fs = new CommandFileSystem();

        $app = new TestApplication();
        $command_db = new CommandDbFileService($fs);
        $alias_db = new AliasDbFileService($fs);
        $io = new DefaultConsoleIO();
        $di = new TestDiContainer();
        $svc = new CommandExecService($fs, $app, $command_db, $alias_db, $io);

        $request = new ShellRequest([
            'David', 'Earl Gray tea', '--age', '21'
        ]);

        $app->request($request);

        ob_start();
        $svc->executeCommand($di, 'foo:bar', 0);
        $output = ob_get_clean();

        $expected = <<<OUTPUT
Installed required module: KnotPhp\Command\Test\TestRequiredModule
Executing command(foo:bar)
My name is: David
And my favorite is: Earl Gray tea
I am 21 years old.
Command(foo:bar) finished with exit code 0
OUTPUT;

        $expected = str_replace("\n", PHP_EOL, $expected) . PHP_EOL;

        $this->assertEquals($expected, $output);;
    }
}
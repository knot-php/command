<?php
require_once __DIR__ . '/include/init.php';

use KnotLib\Di\Container;
use KnotModule\KnotDi\Adapter\KnotDiContainerAdapter;
use KnotPhp\Command\Service\CommandExecService;
use KnotLib\Kernel\Request\PhpArrayRequest;
use KnotPhp\Command\Service\CommandDbFileService;
use KnotPhp\Command\Service\CommandAutoloadService;
use KnotPhp\Command\Service\AliasDbFileService;
use KnotPhp\Command\Command\DefaultConsoleIO;
use KnotPhp\Command\Demo\DemoApplication;
use KnotPhp\Command\Demo\DemoFileSystemFactory;

try{
    $request = new PhpArrayRequest([
        'Arnold Schwarzenegger', 'Terminator', '--verbose' => true
    ]);
    $fs = DemoFileSystemFactory::createFileSystem();
    $app = new DemoApplication($fs);

    $app->request($request);

    $command_db_s = new CommandDbFileService($fs);
    $alias_db_s = new AliasDbFileService($fs);
    $autoload_s = new CommandAutoloadService($fs, $command_db_s);
    $io = new DefaultConsoleIO();
    $exec = new CommandExecService($fs, $app, $command_db_s, $alias_db_s, $io);
    $di = new KnotDiContainerAdapter(new Container());

    $autoload_s->autoload();

    $io->output('Execute command:');
    $exec->executeCommand($di, 'hello:world', 0);

    $io->output('---------------------------');

    $io->output('Execute alias command:');
    $exec->executeCommand($di, 'h:w', 0);

    $io->output('---------------------------');

    $io->output('Verbose switch off:');
    $app->request(new PhpArrayRequest([
        'Michael Jackson', 'MJ', '--verbose' => false
    ]));
    $exec->executeCommand($di, 'h:w', 0);
}
catch(Exception $e)
{
    echo $e->getMessage();
}

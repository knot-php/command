<?php
require_once __DIR__ . '/include/init.php';

use KnotPhp\Command\Service\CommandDbFileService;
use KnotPhp\Command\Service\CommandAutoloadService;
use KnotPhp\Command\Demo\DemoFileSystemFactory;

try{
    $fs = DemoFileSystemFactory::createFileSystem();
    $db_file_s = new CommandDbFileService($fs);
    $autoload_s = new CommandAutoloadService($fs, $db_file_s);

    $autoload_file = $autoload_s->generateAutoloadFile();

    echo 'Generated autoload cache: ' . $autoload_file . PHP_EOL;
}
catch(Exception $e)
{
    echo $e->getMessage();
}


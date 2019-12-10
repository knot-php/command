<?php
require_once __DIR__ . '/include/init.php';

use KnotPhp\Command\Service\CommandDescriptorService;
use KnotPhp\Command\Demo\Hello\World\HelloWorldCommand;
use KnotPhp\Command\Demo\DemoFileSystemFactory;

try{
    $fs = DemoFileSystemFactory::createFileSystem();
    $desc_s = new CommandDescriptorService($fs);

    $descriptor_path = $desc_s->generateCommandDescriptor(HelloWorldCommand::getDescriptor());

    echo 'Generated descriptor: ' . $descriptor_path . PHP_EOL;
}
catch(Exception $e)
{
    echo $e->getMessage();
}


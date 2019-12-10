<?php
require_once __DIR__ . '/include/init.php';

use KnotLib\Kernel\FileSystem\Dir;

use KnotPhp\Command\Service\CommandDescriptorService;
use KnotPhp\Command\Service\CommandDbFileService;
use KnotPhp\Command\Service\AliasDbFileService;
use KnotPhp\Command\Command\Command;
use KnotPhp\Command\Demo\DemoFileSystemFactory;

try{
    $fs = DemoFileSystemFactory::createFileSystem();
    $desc_s = new CommandDescriptorService($fs);
    $db_file_s = new CommandDbFileService($fs);
    $alias_db = new AliasDbFileService($fs);

    $hello_world_descriptor_file = $fs->getFile(Dir::COMMAND, 'hello_world' . Command::COMMAND_DESCRIPTOR_SUFFIX);

    $hello_world_descriptor = $desc_s->readCommandDescriptor($hello_world_descriptor_file);

    $db_file_s->load();

    $db_file_s->setDesciptor($hello_world_descriptor->getCommandId(), $hello_world_descriptor);

    $db_file_s->save();

    $alias_db->importAlias($db_file_s);
    $alias_db->save();

    echo 'Command installed: ' . $hello_world_descriptor->getCommandId() . PHP_EOL;
}
catch(Exception $e)
{
    echo $e->getMessage();
}


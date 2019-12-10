<?php
use KnotPhp\Command\Env\EnvKey;
use KnotPhp\Command\Demo\DemoFileSystemFactory;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

$setting = EnvKey::COMMAND_FILESYSTEM_FACTORY . '=' . DemoFileSystemFactory::class;
putenv($setting);

spl_autoload_register(function ($class)
{
    if (strpos($class, 'Calgamo\\Command\\Demo\\') === 0) {
        $name = substr($class, strlen('Calgamo\\Command\\Demo\\'));
        $name = array_filter(explode('\\',$name));
        $file = __DIR__ . '/' . implode('/',$name) . '.php';
        /** @noinspection PhpIncludeInspection */
        require_once $file;
    }
});

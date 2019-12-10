<?php
$base_dir = dirname(__DIR__);
require_once $base_dir . '/vendor/autoload.php';

spl_autoload_register(function ($class)
{
    if (strpos($class, 'KnotPhp\\Command\\Test\\') === 0) {
        $name = substr($class, strlen('KnotPhp\\Command\\Test\\'));
        $name = array_filter(explode('\\',$name));
        $file = __DIR__ . '/include/' . implode('/',$name) . '.php';
        /** @noinspection PhpIncludeInspection */
        require_once $file;
    }
});
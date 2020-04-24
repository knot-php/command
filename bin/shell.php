<?php
require dirname(__DIR__).'/vendor/autoload.php';

(new KnotLib\Kernel\Bootstrap)
    ->withFileSystem(new KnotPhp\Command\FileSystem\CommandFileSystem())
    ->withExceptionHandler(function(Throwable $e){
        echo $e->getMessage(), PHP_EOL;
        echo '------------------------------------', PHP_EOL;
        echo $e->getTraceAsString(), PHP_EOL;
        exit;
    })
    ->boot(KnotPhp\Command\App\CommandApplication::class);

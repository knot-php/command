<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$env = Dotenv::create(__DIR__);
$env->load();

<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

final class Command
{
    const FILENAME_COMMAND_DB              = 'command_db.json';
    const FILENAME_ALIAS_DB                = 'alias_db.json';
    const FILENAME_COMMAND_AUTOLOAD_CACHE  = 'command_autoload.cache.php';
    const FILENAME_COMMAND_AUTOLOAD_TPL    = 'command_autoload.tpl.php';
    const FILENAME_DESCRIPTOR_TPL          = 'command_descriptor.tpl.php';
    const COMMAND_DESCRIPTOR_SUFFIX        = '.command.json';
}
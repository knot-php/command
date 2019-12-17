<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use KnotLib\Service\DI as ServiceDI;
use KnotLib\Service\UriTrait;

final class DI
{
    use UriTrait;

    //=====================================
    // Components
    //=====================================

    /* Console I/O Component */
    const URI_COMPONENT_CONSOLE_IO       = 'component://console_io';

    //====================================
    // Arrays
    //====================================

    //====================================
    // Strings
    //====================================

    //=====================================
    // Service
    //=====================================

    /* Filesystem Service */
    const URI_SERVICE_FILESYSTEM          = ServiceDI::URI_SERVICE_FILESYSTEM;

    /** Logger Service */
    const URI_SERVICE_LOGGER              = ServiceDI::URI_SERVICE_LOGGER;

    /* System Service */
    const URI_SERVICE_SYSTEM              = 'service://system';

    /* Command Autoload Service */
    const URI_SERVICE_COMMAND_AUTOLOAD    = 'service://command_autoload';

    /* Command DB File Service */
    const URI_SERVICE_COMMAND_DB_FILE     = 'service://command_db_file';

    /* Alias DB File Service */
    const URI_SERVICE_ALIAS_DB_FILE       = 'service://alias_db_file';

    /* Command Descriptor Service */
    const URI_SERVICE_COMMAND_DESCRIPTOR  = 'service://command_descriptor';

    /* Command Execute Service */
    const URI_SERVICE_COMMAND_EXEC        = 'service://command_exec';

}
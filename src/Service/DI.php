<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use KnotLib\Service\DI as ServiceDI;

final class DI
{
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
    const URI_SERVICE_SYSTEM              = ServiceDI::SCHEME_SERVICE . 'system';

    /* Command Autoload Service */
    const URI_SERVICE_COMMAND_AUTOLOAD    = ServiceDI::SCHEME_SERVICE . 'command_autoload';

    /* Command DB File Service */
    const URI_SERVICE_COMMAND_DB_FILE     = ServiceDI::SCHEME_SERVICE . 'command_db_file';

    /* Alias DB File Service */
    const URI_SERVICE_ALIAS_DB_FILE       = ServiceDI::SCHEME_SERVICE . 'alias_db_file';

    /* Command Descriptor Service */
    const URI_SERVICE_COMMAND_DESCRIPTOR  = ServiceDI::SCHEME_SERVICE . 'command_descriptor';

    /* Command Execute Service */
    const URI_SERVICE_COMMAND_EXEC        = ServiceDI::SCHEME_SERVICE . 'command_exec';

}
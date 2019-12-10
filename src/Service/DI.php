<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use KnotLib\Service\DI as ServiceDI;
use KnotLib\Service\DiKeysBase;

final class DI extends DiKeysBase
{
    //=====================================
    // Components
    //=====================================

    /* Component Prefix */
    const COMPONENT                       = self::PREFIX_COMPONENTS;

    /* Console I/O Component */
    const COMPONENT_CONSOLE_IO            = self::PREFIX_COMPONENTS . 'console_io';

    //====================================
    // Arrays
    //====================================

    /* Arrays Prefix */
    const ARRAYS                          = self::PREFIX_ARRAYS;

    //====================================
    // Strings
    //====================================

    /* Arrays Prefix */
    const STRINGS                          = self::PREFIX_STRINGS;

    //=====================================
    // Service
    //=====================================

    /* Service Prefix */
    const SERVICE                         = self::PREFIX_SERVICES;

    /* Filesystem Service */
    const SERVICE_FILESYSTEM              = ServiceDI::SERVICE_FILESYSTEM;

    /** Logger Service */
    const SERVICE_LOGGER                  = ServiceDI::SERVICE_LOGGER;

    /* System Service */
    const SERVICE_SYSTEM                  = self::PREFIX_SERVICES . 'system';

    /* Command Autoload Service */
    const SERVICE_COMMAND_AUTOLOAD        = self::PREFIX_SERVICES . 'command_autoload';

    /* Command DB File Service */
    const SERVICE_COMMAND_DB_FILE         = self::PREFIX_SERVICES . 'command_db_file';

    /* Alias DB File Service */
    const SERVICE_ALIAS_DB_FILE           = self::PREFIX_SERVICES . 'alias_db_file';

    /* Command Descriptor Service */
    const SERVICE_COMMAND_DESCRIPTOR      = self::PREFIX_SERVICES . 'command_descriptor';

    /* Command Execute Service */
    const SERVICE_COMMAND_EXEC            = self::PREFIX_SERVICES . 'command_exec';

}
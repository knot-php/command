<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

final class DescriptorKey
{
    const COMMAND_ID           = 'command_id';
    const ALIASES              = 'aliases';
    const CLASS_ROOT           = 'class_root';
    const CLASS_NAME           = 'class_name';
    const CLASS_BASE           = 'class_base';
    const ORDERED_ARGS         = 'ordered_args';
    const NAMED_ARGS           = 'named_args';
    const COMMAND_HELP         = 'command_help';
}
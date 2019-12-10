<?php
use KnotPhp\Command\Command\CommandDescriptor;

/** @var CommandDescriptor $desc */

$class_root = $desc->getClassRoot();
$class_root = str_replace('\\', '/', $class_root);

$class_name = $desc->getClassName();
$class_name = str_replace('\\', '.', $class_name);

$class_base = $desc->getClassBase();
$class_base = str_replace('\\', '.', $class_base);

$data = [
    'command_id' => $desc->getCommandId(),
    'aliases' => $desc->getAliases(),
    'class_root' => $class_root,
    'class_name' => $class_name,
    'class_base' => $class_base,
    'ordered_args'  => $desc->getOrderdArgs(),
    'named_args' => $desc->getNamedArgs(),
    'command_help' => $desc->getCommandHelp(),
];
echo json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);


<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

use JsonSerializable;

use KnotPhp\Command\Command\DescriptorKey as Key;

final class CommandDescriptor implements JsonSerializable
{
    use ClassNameTrait;

    /** @var string */
    private $command_id;

    /** @var string[] */
    private $aliases;

    /** @var string */
    private $class_root;

    /** @var string */
    private $class_name;

    /** @var string */
    private $class_base;

    /** @var array */
    private $required;

    /** @var array */
    private $ordered_args;

    /** @var array */
    private $named_args;

    /** @var string[] */
    private $command_help;

    /**
     * CommandSpec constructor.
     *
     * @param array $command_spec
     */
    public function __construct(array $command_spec)
    {
        $this->command_id          = $command_spec[Key::COMMAND_ID] ?? '';
        $this->aliases             = $command_spec[Key::ALIASES] ?? [];
        $this->class_root          = $command_spec[Key::CLASS_ROOT] ?? '';
        $this->class_name          = $command_spec[Key::CLASS_NAME] ?? '';
        $this->class_base          = $command_spec[Key::CLASS_BASE] ?? '';
        $this->required            = $command_spec[Key::REQUIRED] ?? [];
        $this->ordered_args        = $command_spec[Key::ORDERED_ARGS] ?? [];
        $this->named_args          = $command_spec[Key::NAMED_ARGS] ?? [];
        $this->command_help        = $command_spec[Key::COMMAND_HELP] ?? [];
    }

    /**
     * @return string
     */
    public function getCommandId() : string
    {
        return $this->command_id;
    }

    /**
     * @return string[]
     */
    public function getAliases() : array
    {
        return $this->aliases;
    }

    /**
     * @return string
     */
    public function getClassName() : string
    {
        return self::getRealClassName($this->class_name);
    }

    /**
     * @return string
     */
    public function getClassRoot() : string
    {
        return $this->class_root;
    }

    /**
     * @return string
     */
    public function getClassBase() : string
    {
        return self::getRealClassName($this->class_base);
    }

    /**
     * @return array
     */
    public function getRequired() : array
    {
        return array_map(function($item){
            return self::getRealClassName($item);
        }, $this->required);
    }

    /**
     * @return array
     */
    public function getOrderdArgs() : array
    {
        return $this->ordered_args;
    }

    /**
     * @return array
     */
    public function getNamedArgs() : array
    {
        return $this->named_args;
    }

    /**
     * @return string[]
     */
    public function getCommandHelp() : array
    {
        return $this->command_help;
    }

    /**
     * @return mixed|void
     */
    public function jsonSerialize()
    {
        return [
            'command_id' => $this->command_id,
            'aliases' => $this->aliases,
            'class_root' => $this->class_root,
            'class_name' => self::getVirtualClassName($this->class_name),
            'class_base' => self::getVirtualClassName($this->class_base),
            'required' => array_map(function($item){
                    return self::getVirtualClassName($item);
                }, $this->required),
            'args' => [
                'ordered' => $this->ordered_args,
                'named' => $this->named_args,
            ],
            'command_help' => $this->command_help,
        ];
    }


}
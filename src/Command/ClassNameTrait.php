<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

trait ClassNameTrait
{
    /**
     * Returns real class name
     * i.e.
     *   Hello\World\My\Class
     *
     * @param string $class_name
     *
     * @return string
     */
    public static function getRealClassName(string $class_name) : string
    {
        return str_replace('.', '\\', $class_name);
    }

    /**
     * Returns virautl class name
     *
     * i.e.
     *   Hello.World.My.Class
     *
     * @param string $class_name
     *
     * @return string
     */
    public static function getVirtualClassName(string $class_name) : string
    {
        return str_replace('\\', '.', $class_name);
    }

    /**
     * Returns class exists
     *
     * @param string $class_name
     *
     * @return bool
     */
    public static function classExists(string $class_name)
    {
        $real_class_name = self::getRealClassName($class_name);
        return class_exists($real_class_name);
    }
}
<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

interface ConsoleIOInterface
{
    /**
     * Input user command from console
     *
     * @param string $message
     *
     * @return string             use input command
     */
    public function ask(string $message) : string;

    /**
     * Output message to console
     *
     * @param string $message
     * @param bool $output_lineend
     */
    public function output(string $message, bool $output_lineend = true);
}
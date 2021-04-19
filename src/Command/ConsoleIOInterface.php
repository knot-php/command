<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

interface ConsoleIOInterface
{
    /**
     * Input user command from console
     *
     * @param string $message               message to show in console
     * @param callable|null $callback       callback to receive user input from console
     *
     * @return ConsoleIOInterface
     */
    public function ask(string $message, callable $callback = null) : ConsoleIOInterface;

    /**
     * Output message to console
     *
     * @param string[] $messages               message to show in console
     *
     * @return ConsoleIOInterface
     */
    public function output(string ... $messages) : ConsoleIOInterface;

    /**
     * Output end of line
     *
     * @return ConsoleIOInterface
     */
    public function eol() : ConsoleIOInterface;
}
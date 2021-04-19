<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

final class DefaultConsoleIO implements ConsoleIOInterface
{
    /**
     * {@inheritDoc}
     */
    public function ask(string $message, callable $callback = null) : ConsoleIOInterface
    {
        echo $message . PHP_EOL;
        if ($callback){
            $input = trim(fgets(STDIN));
            ($callback)($input);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function output(string ... $messages) : ConsoleIOInterface
    {
        foreach($messages as $msg){
            echo $msg;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function eol() : ConsoleIOInterface
    {
        echo PHP_EOL;
        return $this;
    }
}
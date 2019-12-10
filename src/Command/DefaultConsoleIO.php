<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command;

final class DefaultConsoleIO implements ConsoleIOInterface
{
    /**
     * {@inheritDoc}
     */
    public function ask(string $message) : string
    {
        echo $message . PHP_EOL;
        return trim(fgets(STDIN));
    }

    /**
     * {@inheritDoc}
     */
    public function output(string $message, bool $output_lineend = true)
    {
        if ($output_lineend){
            echo $message . PHP_EOL;
        }
        else{
            echo $message;
        }
    }
}
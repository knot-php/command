<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Responder;

use KnotLib\Service\LoggerService;
use KnotPhp\Command\Command\ConsoleIOInterface;

class ErrorCommandResponder extends BaseCommandResponder
{
    /** @var ConsoleIOInterface */
    private $io;

    /**
     * InstallCommandResponder constructor.
     *
     * @param LoggerService $logger
     * @param ConsoleIOInterface $io
     */
    public function __construct(LoggerService $logger, ConsoleIOInterface $io)
    {
        parent::__construct($logger);

        $this->io = $io;
    }

    /**
     * Not Found
     *
     * @param string $path
     * @param string $command_db_file
     */
    public function notFound(string $path, string $command_db_file)
    {
        $message = 'Command Not Found: ' . $path . ' in ' . $command_db_file;

        $this->getLogger()->warning($message);
        $this->io->output($message);
    }
    /**
     * 500: Internal server error
     */
    public function notSupportedMethod()
    {
        $message = 'Not Supported method';
        $this->getLogger()->warning($message);
        $this->io->output($message);
    }

}
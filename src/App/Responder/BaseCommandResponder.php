<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Responder;

use KnotLib\Service\LoggerService;

class BaseCommandResponder
{
    /** @var LoggerService */
    private $logger;

    /**
     * BaseService constructor.
     *
     * @param LoggerService $logger
     */
    public function __construct(LoggerService $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get logger
     *
     * @return LoggerService
     */
    public function getLogger() : LoggerService
    {
        return $this->logger;
    }

    /**
     * failure response
     *
     * @param string $message
     */
    public function failure(string $message)
    {
        $msg = $message ? 'failure: ' . $message : 'failure.';
        $this->logger->error( $msg );
        echo $msg . PHP_EOL;
        exit;
    }
    
    /**
     * success response
     *
     * @param string $message
     */
    public function success(string $message = null)
    {
        $msg = $message ? 'success: ' . $message : 'success.';
        $this->logger->info( $msg );
        echo $msg . PHP_EOL;
        exit;
    }
}
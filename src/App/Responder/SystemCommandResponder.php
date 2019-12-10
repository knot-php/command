<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Responder;

use KnotPhp\Command\Service\SystemService;

class SystemCommandResponder extends BaseCommandResponder
{
    /**
     * Show system version
     *
     * @param SystemService $system
     */
    public function version(SystemService $system)
    {
        $version = $system->getVersion();
        $this->getLogger()->debug('version: ' . $version);
        echo $version . PHP_EOL;
        parent::success();
    }
}
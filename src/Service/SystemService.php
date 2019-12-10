<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use KnotLib\Service\BaseService;

class SystemService extends BaseService
{
    /**
     * returns sytem version
     *
     * @return string
     */
    public function getVersion() : string
    {
        return 'kNot Framework Command System Ver.1.0.0 Released 2019-12-11';
    }
}
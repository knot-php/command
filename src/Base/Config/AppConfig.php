<?php
declare(strict_types=1);

namespace KnotPhp\Command\Base\Config;

use KnotLib\Config\Config;

class AppConfig
{
    const KEY_SITE_NAME      = 'constants/site_name';
    const KEY_SITE_URL       = 'constants/site_url';
    const KEY_ADMIN_NAME     = 'constants/admin_name';
    const KEY_ADMIN_EMAIL    = 'constants/admin_email';
    const KEY_MEMBER_URL     = 'constants/member_url';
    const KEY_ADMIN_URL      = 'constants/admin_url';

    /** @var Config */
    private $config;

    /**
     * AppConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * Returns string config
     *
     * @param string $key
     * @param string $default
     *
     * @return Config
     */
    public function getString(string $key, string $default = '') : string
    {
        return $this->config->getString($key, $default);
    }

    /**
     * Returns string config
     *
     * @param string $key
     * @param bool $default
     *
     * @return Config
     */
    public function getBoolean(string $key, bool $default = false) : string
    {
        return $this->config->getBoolean($key, $default);
    }
}
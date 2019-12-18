<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Module;

use Throwable;

use KnotLib\Di\Container;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\Components;
use KnotLib\Kernel\Module\ModuleInterface;
use KnotLib\Kernel\Module\AbstractModule;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Service\LoggerService;

use KnotPhp\Module\KnotService\KnotServiceModule;

use KnotPhp\Command\Command\DefaultConsoleIO;
use KnotPhp\Command\Service\AliasDbFileService;
use KnotPhp\Command\Service\CommandAutoloadService;
use KnotPhp\Command\Service\CommandDbFileService;
use KnotPhp\Command\Service\CommandDescriptorService;
use KnotPhp\Command\Service\CommandExecService;
use KnotPhp\Command\Base\Config\AppConfig;
use KnotPhp\Command\Service\SystemService;
use KnotPhp\Command\Enum\EnumLogChannels;
use KnotPhp\Command\Service\DI;

class CommandDiModule extends AbstractModule implements ModuleInterface
{
    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponents() : array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public static function declareComponentType(): string
    {
        return Components::MODULE;
    }

    /**
     * Declare dependent on another modules
     *
     * @return array
     */
    public static function requiredModules() : array
    {
        return [
            KnotServiceModule::class
        ];
    }

    /**
     * Install module
     *
     * @param ApplicationInterface $app
     *
     * @throws  ModuleInstallationException
     */
    public function install(ApplicationInterface $app)
    {
        try{
            $c = $app->di();

            $fs = $app->filesystem();

            //====================================
            // Components
            //====================================

            // app_config component
            $c['app_config'] = function(Container $c){
                return new AppConfig($c['arrays.app_config']);
            };
            // components.console_io component
            $c[DI::URI_COMPONENT_CONSOLE_IO] = function(){
                return new DefaultConsoleIO();
            };

            //====================================
            // Arrays
            //====================================

            // arrays.app_config immediate value
            $c['arrays.app_config'] = function(){
                return [
                    'constants' => [
                    ],
                    'is_debug' =>false
                ];
            };

            //====================================
            // Strings
            //====================================

            //====================================
            // Services
            //====================================

            // services.logger factory
            $c->extend(DI::URI_SERVICE_LOGGER, function($component){
                /** @var LoggerService $component */
                $component->setChannelId(EnumLogChannels::COMMAND);
                return $component;
            });
            // services.system factory
            $c[DI::URI_SERVICE_SYSTEM] = function() {
                return new SystemService();
            };
            // services.command_autoload factory
            $c[DI::URI_SERVICE_COMMAND_AUTOLOAD] = function(Container $c) use($fs){
                $db_file = $c[DI::URI_SERVICE_COMMAND_DB_FILE];
                return new CommandAutoloadService($fs, $db_file);
            };
            // services.command_db_file factory
            $c[DI::URI_SERVICE_COMMAND_DB_FILE] = function() use($fs){
                return new CommandDbFileService($fs);
            };
            // services.alias_db_file factory
            $c[DI::URI_SERVICE_ALIAS_DB_FILE] = function() use($fs){
                return new AliasDbFileService($fs);
            };
            // services.command_descriptor factory
            $c[DI::URI_SERVICE_COMMAND_DESCRIPTOR] = function() use($fs){
                return new CommandDescriptorService($fs);
            };
            // services.command_exec factory
            $c[DI::URI_SERVICE_COMMAND_EXEC] = function(Container $c) use($fs, $app){
                $command_db = $c[DI::URI_SERVICE_COMMAND_DB_FILE];
                $alias_db  = $c[DI::URI_SERVICE_ALIAS_DB_FILE];
                $io = $c[DI::URI_COMPONENT_CONSOLE_IO];
                return new CommandExecService($fs, $app, $command_db, $alias_db, $io);
            };

        }
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }
}
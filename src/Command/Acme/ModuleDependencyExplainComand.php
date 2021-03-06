<?php
declare(strict_types=1);

namespace KnotPhp\Command\Command\Acme;

use ReflectionClass, Throwable;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Module\ModuleDependencyResolver;
use KnotPhp\Command\Command\AbstractCommand;
use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Command\CommandInterface;
use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\Command\Exception\CommandExecutionException;

use KnotPhp\Command\Command\DescriptorKey as Key;

final class ModuleDependencyExplainComand extends AbstractCommand implements CommandInterface
{
    /**
     * Returns command id
     *
     * @return string
     */
    public static function getCommandId() : string
    {
        return 'module:dependency:explain';
    }

    /**
     * Returns command descriptor
     *
     * @return CommandDescriptor
     */
    public static function getDescriptor() : CommandDescriptor
    {
        return new CommandDescriptor([
            Key::COMMAND_ID => self::getCommandId(),
            Key::ALIASES => [
                'mod:dependency:explain',
                'mod:dependency:exp',
                'mod:dep:explain',
                'mod:dep:exp',
            ],
            Key::CLASS_ROOT => dirname(dirname(__DIR__)),
            Key::CLASS_NAME => str_replace('\\', '.', self::class),
            Key::CLASS_BASE => 'KnotPhp.Command',
            Key::REQUIRED => [],
            Key::ORDERED_ARGS => ['app_class'],
            Key::NAMED_ARGS => [],
            Key::COMMAND_HELP => [
                'knot module:dependency:explain app_class',
                'knot mod:dependency:exp password app_class',
                'knot mod:dep:explain password app_class',
                'knot mod:dep:exp password app_class',
            ]
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $args, ConsoleIOInterface $io): int
    {
        $io->output('cmd: ' . self::getCommandId())->eol();

        $app_class= $args['app_class'] ?? '';

        $io->output('app_class: ' . $app_class)->eol();

        if (empty($app_class)){
            throw new CommandExecutionException($this->getCommandId(), 'Parameter[app_class] must be specified.');
        }

        $app_class = str_replace('.', '\\', $app_class);

        if (!class_exists($app_class)){
            throw new CommandExecutionException($this->getCommandId(), "Can not find the specified class: {$app_class}.");
        }
        if (!in_array(ApplicationInterface::class, class_implements($app_class))){
            throw new CommandExecutionException($this->getCommandId(), "The specified class[$app_class] does not seems to be an application class.");
        }

        try{
            $fs = $this->getRuntimeFileSystem();

            /** @var ApplicationInterface $app */
            $app = (new ReflectionClass($app_class))->newInstance($fs);

            $app->configure();

            $required_modules = $app->getRequiredModules();

            $io->output('required modules:' . print_r($required_modules, true))->eol();

            $resolved_modules = (new ModuleDependencyResolver($required_modules))->resolve(function($dependency_map, $modules_by_component, $sort_logs) use($io){

                $io->output('dependency map:' . print_r($dependency_map, true))->eol();
                $io->output('modules by component:' . print_r($modules_by_component, true))->eol();
                $io->output('sort logs:' . print_r($sort_logs, true))->eol();

            });

            $io->output('resolved modules:' . print_r($resolved_modules, true))->eol();

            $io->output('OK.')->eol();
        }
        catch(Throwable $e){

            $io->output('Finished with error:' . $e->getMessage())->eol();
            $io->output($e->getTraceAsString())->eol();
        }

        return 0;
    }
}
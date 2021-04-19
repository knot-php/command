<?php
declare(strict_types=1);

namespace KnotPhp\Command\App\Responder;

use KnotPhp\Command\Command\Command;
use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Command\CommandDescriptorProviderInterface;
use KnotPhp\Command\Command\ConsoleIOInterface;
use KnotPhp\Command\Exception\ClassImplementationException;
use KnotPhp\Command\Exception\ClassNotFoundException;
use KnotPhp\Command\Service\AliasDbFileService;
use KnotPhp\Command\Service\CommandDbFileService;
use KnotPhp\Command\Service\CommandDescriptorService;
use KnotPhp\Command\Service\CommandAutoloadService;
use KnotPhp\Command\Service\CommandExecService;
use KnotPhp\Command\Exception\CommandException;
use KnotLib\Kernel\Di\DiContainerInterface;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Service\LoggerService;

class CommandCommandResponder extends BaseCommandResponder
{
    const NAMESPACE_SEPARATOR = '\\';

    const CONSOLE_SEPARATOR = '-----------------------------------------------------------------------------------------';

    /** @var DiContainerInterface */
    private $di;

    /** @var FileSystemInterface */
    private $fs;

    /** @var ConsoleIOInterface */
    private $io;

    /**
     * InstallCommandResponder constructor.
     *
     * @param DiContainerInterface $di
     * @param LoggerService $logger
     * @param FileSystemInterface $fs
     * @param ConsoleIOInterface $io
     */
    public function __construct(DiContainerInterface $di, LoggerService $logger, FileSystemInterface $fs, ConsoleIOInterface $io)
    {
        parent::__construct($logger);

        $this->di = $di;
        $this->fs = $fs;
        $this->io = $io;
    }

    /**
     * Make command(create descriptor file)
     *
     * @param CommandDescriptorService $desc_s
     * @param string $provider_class
     */
    public function makeCommand(CommandDescriptorService $desc_s, string $provider_class)
    {
        try{
            $provider_class = str_replace('.', self::NAMESPACE_SEPARATOR, $provider_class);

            if (!class_exists($provider_class)){
                throw new ClassNotFoundException($provider_class);
            }
            if (!in_array(CommandDescriptorProviderInterface::class, class_implements($provider_class))){
                throw new ClassImplementationException($provider_class, CommandDescriptorProviderInterface::class);
            }

            $this->io->output(self::CONSOLE_SEPARATOR)->eol();

            $descriptor_list = forward_static_call([$provider_class, 'provide'], $this->fs);

            $total = 0;
            foreach($descriptor_list as $descriptor){
                $descriptor_path = $desc_s->generateCommandDescriptor($descriptor);

                $this->io->output(sprintf('Generated descriptor: [%s]', basename($descriptor_path)))->eol();
                $total ++;
            }

            $this->io->output(self::CONSOLE_SEPARATOR)->eol();

            $this->io->output(sprintf('Made %d command descriptor(s).', $total))->eol();
        }
        catch(CommandException $e){

            parent::failure($e->getMessage());
        }
    }

    /**
     * Install command
     *
     * @param CommandDescriptorService $desc_s
     * @param CommandDbFileService $command_db
     * @param AliasDbFileService $alias_db
     * @param string $command_id
     */
    public function installCommand(CommandDescriptorService $desc_s, CommandDbFileService $command_db, AliasDbFileService $alias_db, string $command_id)
    {
        try{
            $this->io->output(self::CONSOLE_SEPARATOR)->eol();

            $command_db->load();

            $total = 0;
            if ($command_id === 'all' || empty($command_id)){
                // read all descriptor files
                $command_dir = $this->fs->getFile(Dir::COMMAND, '*' . Command::COMMAND_DESCRIPTOR_SUFFIX);
                foreach(glob($command_dir) as $descriptor_file){
                    $descriptor = $desc_s->readCommandDescriptor($descriptor_file);

                    $command_db->setDesciptor($descriptor->getCommandId(), $descriptor);

                    $this->io->output(sprintf('Command installed: [%s]', $descriptor->getCommandId()))->eol();
                    $total ++;
                }
            }
            else{
                $descriptor_file = $desc_s->commandIdToDescriptorFile($command_id);

                $descriptor = $desc_s->readCommandDescriptor($descriptor_file);

                $command_db->setDesciptor($descriptor->getCommandId(), $descriptor);

                $this->io->output(sprintf('Command installed: [%s]', $descriptor->getCommandId()))->eol();
                $total ++;
            }

            $command_db->save();

            $alias_db->importAlias($command_db);
            $alias_db->save();

            $this->io->output(self::CONSOLE_SEPARATOR)->eol();

            $this->io->output(sprintf('Saved %d commands into database.', $total))->eol();
        }
        catch(CommandException $e){

            parent::failure($e->getMessage());
        }
    }

    /**
     * list command
     *
     * @param CommandDescriptorService $desc_s
     */
    public function listCommand(CommandDescriptorService $desc_s)
    {
        try{
            $total = 0;

            $this->io->output(str_pad('ID', 25))->eol();

            $this->io->output(self::CONSOLE_SEPARATOR)->eol();

            // read all descriptor files
            $command_dir = $this->fs->getFile(Dir::COMMAND, '*' . Command::COMMAND_DESCRIPTOR_SUFFIX);
            foreach(glob($command_dir) as $descriptor_file){
                $descriptor = $desc_s->readCommandDescriptor($descriptor_file);

                $this->io->output($descriptor->getCommandId())->eol();
                $total ++;
            }

            $this->io->output(self::CONSOLE_SEPARATOR)->eol();

            $this->io->output(sprintf('%d commands found in database.', $total))->eol();
        }
        catch(CommandException $e){

            parent::failure($e->getMessage());
        }
    }

    /**
     * help command
     *
     * @param CommandDescriptorService $desc_s
     * @param string $command_id
     */
    public function helpCommand(CommandDescriptorService $desc_s, string $command_id)
    {
        try{
            $this->io->output(str_pad('ID', 25) . 'COMMAND LINE')->eol();

            $this->io->output(self::CONSOLE_SEPARATOR)->eol();

            if ($command_id === 'all' || empty($command_id)){
                // read all descriptor files
                $command_dir = $this->fs->getFile(Dir::COMMAND, '*' . Command::COMMAND_DESCRIPTOR_SUFFIX);
                $files = glob($command_dir);
                foreach($files as $key => $descriptor_file){
                    $desc = $desc_s->readCommandDescriptor($descriptor_file);
                    $this->showCommandHelp($desc, $key < count($files) - 1);
                }
            }
            else{
                $descriptor_file = $desc_s->commandIdToDescriptorFile($command_id);
                $desc = $desc_s->readCommandDescriptor($descriptor_file);
                $this->showCommandHelp($desc, false);
            }

            $this->io->output(self::CONSOLE_SEPARATOR)->eol();
        }
        catch(CommandException $e){

            parent::failure($e->getMessage());
        }
    }

    private function showCommandHelp(CommandDescriptor $desc, bool $show_separatpr)
    {
        $command_help = $desc->getCommandHelp();

        if (is_array($command_help)){
            foreach($command_help as $key => $help){
                $line = $key === 0 ? str_pad($desc->getCommandId(), 25) . $help : str_repeat(' ', 25) . $help;
                $this->io->output($line)->eol();
            }
            if ($show_separatpr){
                $this->io->output(self::CONSOLE_SEPARATOR)->eol();
            }
        }
        else if (is_string($command_help)){
            $line = str_pad($desc->getCommandId(), 25) . $command_help;
            $this->io->output($line)->eol();
        }
    }

    /**
     * Generate autoload cahe
     *
     * @param CommandAutoloadService $autoload_s
     */
    public function generateAutoloadCache(CommandAutoloadService $autoload_s)
    {
        try{
            $autoload_file = $autoload_s->generateAutoloadFile();

            $this->io->output('Generated autoload cache: ' . $autoload_file)->eol();
        }
        catch(CommandException $e){

            parent::failure($e->getMessage());
        }
    }

    /**
     * Execute command
     *
     * @param CommandAutoloadService $autoload_s
     * @param CommandExecService $exec_s
     * @param string $command_id
     * @param int $skip_args
     */
    public function execute(CommandAutoloadService $autoload_s, CommandExecService $exec_s, string $command_id, int $skip_args)
    {
        try{
            $autoload_s->autoload();
            $exec_s->executeCommand($this->di, $command_id, $skip_args);
        }
        catch(CommandException $e){

            parent::failure($e->getMessage());
        }
    }
}
<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use Stk2k\File\File;

use KnotPhp\Command\Command\Command;
use KnotPhp\Command\Command\CommandDescriptor;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Kernel\FileSystem\FileSystemInterface;

final class CommandDbFileService extends CommandBaseService
{
    /** @var string */
    private $command_db_file;

    /** @var CommandDescriptor[] */
    private $command_db;

    /**
     * CommandInstallService constructor.
     *
     * @param FileSystemInterface $fs
     *
     * @throws
     */
    public function __construct(FileSystemInterface $fs)
    {
        parent::__construct($fs);

        $this->command_db_file = $this->getFileSystem()->getFile(Dir::DATA, Command::FILENAME_COMMAND_DB);
        $this->command_db = [];
    }

    /**
     * @return string
     */
    public function getCommandDbFile() : string
    {
        return $this->command_db_file;
    }

    /**
     * @return CommandDescriptor[]
     */
    public function getCommandDb() : array
    {
        return $this->command_db;
    }

    /**
     * Returns decriptor
     *
     * @param string $command_id
     *
     * @return CommandDescriptor|null
     */
    public function getDesciptor(string $command_id)
    {
        return $this->command_db[$command_id] ?? null;
    }

    /**
     * Set descriptor
     *
     * @param string $command_id
     * @param CommandDescriptor $descriptor
     */
    public function setDesciptor(string $command_id, CommandDescriptor $descriptor)
    {
        $this->command_db[$command_id] = $descriptor;
    }

    /**
     * Load command DB
     *
     * @return bool
     */
    public function load() : bool
    {
        $this->command_db = [];

        if (!file_exists($this->command_db_file)){
            return true;
        }
        $contents = file_get_contents($this->command_db_file);
        if (empty($contents)){
            return true;
        }
        $descriptor_list = json_decode($contents, true);
        if (!is_array($descriptor_list)){
            return true;
        }

        $command_db = [];

        foreach($descriptor_list as $key => $descriptor){
            $command_id         = $descriptor['command_id'] ?? '';
            $aliases            = $descriptor['aliases'] ?? [];
            $class_root         = $descriptor['class_root'] ?? '';             // i.e: /root/to/my/command
            $class_name         = $descriptor['class_name'] ?? '';             // i.e: MyPackage.MyCommand
            $class_base         = $descriptor['class_base'] ?? '';             // i.e: MyPackage
            $required           = $descriptor['required'] ?? [];
            $ordered_args       = $descriptor['args']['ordered'] ?? [];
            $named_args         = $descriptor['args']['named'] ?? [];
            $command_help       = $descriptor['command_help'] ?? '';

            $command_db[$command_id] = new CommandDescriptor([
                'command_id' => $command_id,
                'aliases' => $aliases,
                'class_root' => $class_root,
                'class_name' => $class_name,
                'class_base' => $class_base,
                'required' => $required,
                'ordered_args' => $ordered_args,
                'named_args' => $named_args,
                'command_help' => $command_help,
            ]);
        }

        $this->command_db = $command_db;

        return true;
    }

    /**
     * Save command DB
     *
     * @throws
     */
    public function save()
    {
        $contents = json_encode($this->command_db, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

        (new File($this->command_db_file))->getParent()->makeDirectory();

        file_put_contents($this->command_db_file, $contents);
    }


}
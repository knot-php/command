<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use Stk2k\File\File;

use KnotPhp\Command\Command\Command;
use KnotPhp\Command\Command\CommandDescriptor;
use KnotPhp\Command\Exception\DescriptorFileFormatException;
use KnotPhp\Command\Exception\DescriptorFileNotFoundException;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Kernel\FileSystem\FileSystemInterface;

class CommandDescriptorService extends CommandBaseService
{
    const NAMESPACE_SEPARATOR = '\\';

    /** @var string */
    private $descriptor_template_file;

    /**
     * CommandSpecService constructor.
     *
     * @param FileSystemInterface $fs
     */
    public function __construct(FileSystemInterface $fs)
    {
        parent::__construct($fs);

        $this->descriptor_template_file = $this->getFileSystem()->getFile(Dir::TEMPLATE, Command::FILENAME_DESCRIPTOR_TPL);
    }

    /**
     * Returns command descriptor path from command id
     *
     * @param string $command_id
     *
     * @return string
     *
     * @throws
     */
    public function commandIdToDescriptorFile(string $command_id) : string
    {
        $filename_base = str_replace(':', '_', $command_id);

        return $this->getFileSystem()->getFile(Dir::COMMAND, $filename_base . Command::COMMAND_DESCRIPTOR_SUFFIX);
    }

    /**
     * Generate command descriptor
     *
     * @param CommandDescriptor $desc
     *
     * @return string
     *
     * @throws
     */
    public function generateCommandDescriptor(CommandDescriptor $desc) : string
    {
        $descriptor_file = $this->commandIdToDescriptorFile($desc->getCommandId());

        (new File($descriptor_file))->getParent()->makeDirectory();

        ob_start();
        /** @noinspection PhpIncludeInspection */
        require $this->descriptor_template_file;
        $contents = ob_get_clean();

        file_put_contents($descriptor_file, $contents);

        return $descriptor_file;
    }

    /**
     * Read command descriptor file
     *
     * @param string $descriptor_file
     *
     * @return CommandDescriptor
     *
     * @throws DescriptorFileNotFoundException
     * @throws DescriptorFileFormatException
     */
    public function readCommandDescriptor(string $descriptor_file) : CommandDescriptor
    {
        if (!is_readable($descriptor_file)){
            throw new DescriptorFileNotFoundException($descriptor_file);
        }

        $descriptor = json_decode(file_get_contents($descriptor_file), true);

        if (!is_array($descriptor)){
            throw new DescriptorFileFormatException($descriptor_file, 'Top level of command spec must be an array.');
        }

        $command_id          = $descriptor['command_id'] ?? '';
        $aliases             = $descriptor['aliases'] ?? [];
        $class_root          = $descriptor['class_root'] ?? '';             // i.e: /root/to/my/command
        $class_name          = $descriptor['class_name'] ?? '';             // i.e: MyPackage.MyCommand
        $class_base          = $descriptor['class_base'] ?? '';             // i.e: MyPackage
        $ordered_args        = $descriptor['ordered_args'] ?? [];
        $named_args          = $descriptor['named_args'] ?? [];
        $command_help        = $descriptor['command_help'] ?? [];

        if (empty($command_id)){
            throw new DescriptorFileFormatException($descriptor_file, 'No command id found.');
        }
        if (empty($class_root) || !file_exists($class_root) || !is_dir($class_root)){
            throw new DescriptorFileFormatException($descriptor_file, 'Class root not found: ' . $command_id);
        }
        if (empty($class_name) || !file_exists($class_root)){
            throw new DescriptorFileFormatException($descriptor_file, 'Class root not found: ' . $command_id);
        }
        if (empty($command_help)){
            throw new DescriptorFileFormatException($descriptor_file, 'Command help is mandatory: ' . $command_id);
        }

        $spec = [
            'command_id' => $command_id,
            'aliases' => $aliases,
            'class_root' => $class_root,
            'class_name' => str_replace('.', self::NAMESPACE_SEPARATOR, $class_name),
            'class_base' => str_replace('.', self::NAMESPACE_SEPARATOR, $class_base),
            'ordered_args' => $ordered_args,
            'named_args' => $named_args,
            'command_help' => $command_help,
        ];

        return new CommandDescriptor($spec);
    }

}
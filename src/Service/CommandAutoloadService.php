<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use KnotPhp\Command\FileSystem\CommandFileSystem;
use KnotPhp\Command\Command\Command;
use KnotPhp\Command\Exception\TemplateFileNotFoundException;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use Stk2k\File\File;

final class CommandAutoloadService extends CommandBaseService
{
    /** @var string */
    private $autoload_tpl_file;

    /** @var string */
    private $autoload_cache_file;

    /** @var CommandDbFileService */
    private $db_file;

    /**
     * CommandClassMapCacheService constructor.
     *
     * @param FileSystemInterface $fs
     * @param CommandDbFileService $db_file
     *
     * @throws
     */
    public function __construct(FileSystemInterface $fs, CommandDbFileService $db_file)
    {
        parent::__construct($fs);

        $this->db_file = $db_file;

        $this->autoload_tpl_file = (new CommandFileSystem())->getFile(Dir::TEMPLATE, Command::FILENAME_COMMAND_AUTOLOAD_TPL);

        $this->autoload_cache_file = $this->getFileSystem()->getFile(Dir::CACHE, Command::FILENAME_COMMAND_AUTOLOAD_CACHE);
    }

    /**
     * Generate command class map cache for autoloading
     *
     * @return string
     *
     * @throws
     */
    public function generateAutoloadFile() : string
    {
        if (!is_readable($this->autoload_tpl_file)){
            throw new TemplateFileNotFoundException($this->autoload_tpl_file);
        }

        $this->db_file->load();

        extract([
            'command_db' => $this->db_file->getCommandDb()
        ]);

        ob_start();
        /** @noinspection PhpIncludeInspection */
        require $this->autoload_tpl_file;
        $contents = ob_get_clean();

        $php_code = '<?php' . PHP_EOL . $contents;

        (new File($this->autoload_cache_file))->getParent()->makeDirectory();

        file_put_contents($this->autoload_cache_file, $php_code);

        return $this->autoload_cache_file;
    }

    /**
     * Load command autoload
     *
     * @throws TemplateFileNotFoundException
     */
    public function autoload()
    {
        if (!is_readable($this->autoload_cache_file)){
            $this->generateAutoloadFile();
        }

        /** @noinspection PhpIncludeInspection */
        require $this->autoload_cache_file;
    }
}
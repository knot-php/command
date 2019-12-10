<?php
declare(strict_types=1);

namespace KnotPhp\Command\Service;

use KnotPhp\Command\Command\Command;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use Stk2k\File\File;

final class AliasDbFileService extends CommandBaseService
{
    /** @var string */
    private $alias_db_file;

    /** @var array */
    private $alias_db;

    /**
     * AliasDbFileService constructor.
     *
     * @param FileSystemInterface $fs
     *
     * @throws
     */
    public function __construct(FileSystemInterface $fs)
    {
        parent::__construct($fs);

        $this->alias_db_file = $this->getFileSystem()->getFile(Dir::DATA, Command::FILENAME_ALIAS_DB);
        $this->alias_db = [];
    }

    /**
     * @return array
     */
    public function getAliasDB() : array
    {
        return $this->alias_db;
    }

    /**
     * Returns whether alias exists
     *
     * @param string $alias
     *
     * @return bool
     */
    public function isAlias(string $alias) : bool
    {
        return isset($this->alias_db[$alias]);
    }

    /**
     * Get command id
     *
     * @param string $alias
     *
     * @return string
     */
    public function getCommandId(string $alias) : string
    {
        return $this->alias_db[$alias] ?? '';
    }

    /**
     * Set alias
     *
     * @param string $command_id
     * @param string $alias
     */
    public function setAlias(string $command_id, string $alias)
    {
        $this->alias_db[$alias] = $command_id;
    }

    /**
     * Import alias from command DB
     *
     * @param CommandDbFileService $db_file
     */
    public function importAlias(CommandDbFileService $db_file)
    {
        $this->alias_db = [];
        foreach($db_file->getCommandDb() as $item){
            $command_id = $item->getCommandId();
            foreach($item->getAliases() as $alias){
                $this->alias_db[$alias] = $command_id;
            }
        }
    }

    /**
     * Load command alias DB file
     *
     * @return bool
     */
    public function load() : bool
    {
        $this->alias_db = [];

        if (!file_exists($this->alias_db_file)){
            return true;
        }

        $contents = file_get_contents($this->alias_db_file);
        if (empty($contents)){
            return true;
        }

        $alias_db = json_decode($contents, true);
        if (!is_array($alias_db)){
            return true;
        }

        $this->alias_db = $alias_db;

        return true;
    }

    /**
     * Generate command alias DB file
     *
     * @return string
     *
     * @throws
     */
    public function save() : string
    {
        $contents = json_encode($this->alias_db, JSON_PRETTY_PRINT);

        (new File($this->alias_db_file))->getParent()->makeDirectory();

        file_put_contents($this->alias_db_file, $contents);

        return $this->alias_db_file;
    }
}
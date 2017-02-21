<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 21.1.17
 * Time: 19:42
 */

namespace App\CoreModule\Model;


use App\Model\BaseManager;
use App\Model\DatabaseHelper;
use Nette\Database\Context;
use Nette\Database\Table\IRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;

/**
 * Service to work with articles
 * Class ResourceManager
 * @package App\CoreModule\Model
 */
abstract class EntityManager extends BaseManager
{

    protected $tableName;
    protected $primaryKey;
    protected $uniques;
    protected $columns;

    /**
     * EntityManager constructor.
     * @param Context $database
     * @param DatabaseHelper $databaseHelper
     * @param $tableName
     */
    public function __construct(Context $database, DatabaseHelper $databaseHelper, $tableName)
    {
        parent::__construct($database, $databaseHelper);
        $this->tableName = $tableName;
        $table = $this->databaseHelper->getTable($tableName);
        $this->primaryKey = $table->getPrimaryKey();
        $this->uniques = $table->getUniques();
        $this->columns = $table->getComlumnNames();
    }

    /**
     * @return mixed name of column with ID
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Returns entities from DB
     * @param int $limit amount of articles
     * @param int $offset first article to fetch
     * @return Selection seznam článků
     */
    public function getEntities(int $limit = null, int $offset = null): Selection
    {
        return $this->database->table($this->tableName)->order($this->primaryKey . " " . 'DESC')
            ->limit($limit, $offset);
    }

    /**
     * Returns entity from DB by url/name
     * @param string $unique URL článku
     * @return bool|mixed|IRow první článek, který odpovídá URL nebo false při neúspěchu
     */

    public function getEntityByUnique(string $unique)
    {
        return $this->database->table($this->tableName)->where($this->uniques[0], $unique)->fetch();
    }

    /**
     * @param int $primaryKey
     * @return IRow
     */
    public function getEntityByPrimaryKey(int $primaryKey): IRow
    {
        return $this->database->table($this->tableName)->wherePrimary($primaryKey)->fetch();
    }

    /**
     * Saves entity to DB. If not set ID, creates new, otherwise edits existing one
     * @param array|ArrayHash $entityData
     */
    public function saveEntity(array $entityData)
    {
        if (empty($entityData[$this->primaryKey])) {
            $entityData[$this->primaryKey] = null;
            $this->database->table($this->tableName)->insert($entityData);
        } else {
            $this->database->table($this->tableName)->wherePrimary($entityData[$this->primaryKey])->update($entityData);
        }
    }

    /**
     * Removes entity from DB
     * @param string $unique URL článku
     */
    public function deleteEntity(string $unique)
    {
        $this->database->table($this->tableName)->where($this->uniques[0], $unique)->delete();
    }

    /**
     * @param string $unique
     * @return bool
     */
    public function entityExists(string $unique): bool
    {
        if ($this->getEntityByUnique($unique) != false)
            return true;
        else
            return false;
    }

    /**
     * @return Selection table which belongs to presenter
     */
    public function getTable()
    {
        return $this->database->table($this->tableName);
    }

    /**
     * Vrátí databázi
     * @return \App\Model\Content|\Nette\Database\Context databáze
     */
    public function getDatabase()
    {
        return $this->database;
    }
}

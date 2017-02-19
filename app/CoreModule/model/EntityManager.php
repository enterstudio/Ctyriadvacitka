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
        $this->primaryKey = $this->databaseHelper->getTable($tableName)->getPrimaryKey();
        $this->columns = $this->databaseHelper->getTable($tableName)->getComlumnNames();
    }

    const
        COLUMN_TITLE = 'title',
        COLUMN_CONTENT = 'content',
        COLUMN_URL = 'url',
        COLUMN_DESCRIPTION = 'description',
        COLUMN_REQUESTABLE = 'requestable';

    /**
     * @return mixed name of column with ID
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Vrátí seznam článků v databázi
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
     * vrátí článek z databáze podle jeho URL
     * @param string $url URL článku
     * @return bool|mixed|IRow první článek, který odpovídá URL nebo false při neúspěchu
     */

    public function getEntity(string $url)
    {
        return $this->database->table($this->tableName)->where(self::COLUMN_URL, $url)->fetch();
    }

    /**
     * Uloží článek do systému. Pokud není nastaveno ID, vloží nový, jinak provede editaci.
     * @param array|ArrayHash $article
     */
    public function saveEntity(array $article)
    {
        if (empty($article[$this->primaryKey]))
            $this->database->table($this->tableName)->insert(
                array(
                    self::COLUMN_TITLE => $article[self::COLUMN_TITLE],
                    self::COLUMN_CONTENT => $article[self::COLUMN_CONTENT],
                    self::COLUMN_URL => $article[self::COLUMN_URL],
                    self::COLUMN_DESCRIPTION => $article[self::COLUMN_DESCRIPTION],
                    self::COLUMN_REQUESTABLE => $article[self::COLUMN_REQUESTABLE]
                )
            );
        else {
            $this->database->table($this->tableName)->wherePrimary($article[$this->primaryKey])->update(
                array(
                    $this->primaryKey => $article[$this->primaryKey],
                    self::COLUMN_TITLE => $article[self::COLUMN_TITLE],
                    self::COLUMN_CONTENT => $article[self::COLUMN_CONTENT],
                    self::COLUMN_URL => $article[self::COLUMN_URL],
                    self::COLUMN_DESCRIPTION => $article[self::COLUMN_DESCRIPTION],
                    self::COLUMN_REQUESTABLE => $article[self::COLUMN_REQUESTABLE]
                )
            );
        }
    }

    /**
     * Odstraní článek
     * @param string $url URL článku
     */
    public function deleteEntity(string $url)
    {
        $this->database->table($this->tableName)->where(self::COLUMN_URL, $url)->delete();
    }

    /**
     * @return Selection table which belongs to presenter
     */
    public function getTable()
    {
        return $this->database->table($this->tableName);
    }
}
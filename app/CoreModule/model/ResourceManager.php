<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 21.1.17
 * Time: 19:42
 */

namespace App\CoreModule\Model;


use App\Model\BaseManager;
use Nette\Database\Table\IRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;

/**
 * Service to work with articles
 * Class ResourceManager
 * @package App\CoreModule\Model
 */
class ResourceManager extends BaseManager
{

    protected $tableName;
    protected $columnID;

    /**
     * @return mixed name of column with ID
     */
    public function getColumnID()
    {
        return $this->columnID;
    }
    const
        COLUMN_TITLE = 'title',
        COLUMN_CONTENT = 'content',
        COLUMN_URL = 'url',
        COLUMN_DESCRIPTION = 'description';
    /**
     * Vrátí seznam článků v databázi
     * @return Selection seznam článků
     */
    public function getArticles():Selection{
        return $this->database->table($this->tableName)->order($this->columnID . " " . 'DESC');
    }

    /**
     * vrátí článek z databáze podle jeho URL
     * @param string $url URL článku
     * @return bool|mixed|IRow první článek, který odpovídá URL nebo false při neúspěchu
     */

    public function getArticle(string $url){
        return $this->database->table($this->tableName)->where(self::COLUMN_URL, $url)->fetch();
    }

    /**
     * Uloží článek do systému. Pokud není nastaveno ID, vloží nový, jinak provede editaci.
     * @param array|ArrayHash $article
     */
    public function saveArticle(array $article){
        if (empty($article[$this->columnID]))
            $this->database->table($this->tableName)->insert(
                array(
                    self::COLUMN_TITLE => $article[self::COLUMN_TITLE],
                    self::COLUMN_CONTENT => $article[self::COLUMN_CONTENT],
                    self::COLUMN_URL => $article[self::COLUMN_URL],
                    self::COLUMN_DESCRIPTION => $article[self::COLUMN_DESCRIPTION]
                )
            );
        else {
            $this->database->table($this->tableName)->wherePrimary($article[$this->columnID])->update(
                array(
                    $this->columnID => $article[$this->columnID],
                    self::COLUMN_TITLE => $article[self::COLUMN_TITLE],
                    self::COLUMN_CONTENT => $article[self::COLUMN_CONTENT],
                    self::COLUMN_URL => $article[self::COLUMN_URL],
                    self::COLUMN_DESCRIPTION => $article[self::COLUMN_DESCRIPTION]
                )
            );
        }
    }

    /**
     * Odstraní článek
     * @param string $url URL článku
     */
    public function deleteArticle(string $url){
        $this->database->table($this->tableName)->where(self::COLUMN_URL, $url)->delete();
    }
}
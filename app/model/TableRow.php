<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 8.2.17
 * Time: 15:06
 */

namespace app\CoreModule\model;


use Nette\Database\Table\ActiveRow;
use Nette\Object;

/**
 * Simplified row of table
 * Class TableRow
 * @package app\CoreModule\model
 */
class TableRow extends Object
{
    public
        $news_id,
        $article_id,
        $title,
        $content,
        $url,
        $description,
        $requestable,
        $author;

    /**
     * TableRow constructor.
     * @param ActiveRow $row
     * @param array $columns
     */
    public function __construct(ActiveRow $row, array $columns)
    {
        foreach ($columns as $column) {
            $this->{$column} = $row->{$column};
        }
    }

    /**
     * @return mixed content of article
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content content of article
     */
    public function setContent($content)
    {
        $this->content = $content;
    }


}

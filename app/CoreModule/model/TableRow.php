<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 8.2.17
 * Time: 15:06
 */

namespace app\CoreModule\model;


use Nette\Database\Table\ActiveRow;

/**
 * Simplified row of table
 * Class TableRow
 * @package app\CoreModule\model
 */
class TableRow
{
    public
        $news_id,
        $title,
        $content,
        $url,
        $description,
        $requestable;

    public function __construct(ActiveRow $row){
        $columns = get_object_vars($this);
        foreach ($columns as $column => $value){
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
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CoreModule\Model;

use App\Model\DatabaseHelper;
use Nette\Database\Context;

/**
 * Třída poskytuje metody pro správu článků v RS
 * @package App\CoreModule\Module
 * @author matyas
 */
class ArticleManager extends ResourceManager
{
    public function __construct(Context $database, DatabaseHelper $databaseHelper)
    {
        parent::__construct($database, $databaseHelper);
        $this->columnID = 'article_id';
        $this->tableName = 'article';
    }
}

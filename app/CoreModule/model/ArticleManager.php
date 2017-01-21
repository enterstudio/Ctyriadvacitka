<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CoreModule\Model;

use Nette\Database\Context;
use Nette\Security\User;

/**
 * Třída poskytuje metody pro správu článků v RS
 * @package App\CoreModule\Module
 * @author matyas
 */
class ArticleManager extends ResourceManager{
    public function __construct(Context $database, User $user)
    {
        parent::__construct($database, $user);
        $this->columnID = 'article_id';
        $this->tableName = 'article';
    }
}

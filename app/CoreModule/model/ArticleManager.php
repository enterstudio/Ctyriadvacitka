<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CoreModule\Model;

use App\Model\BaseManager;
use Nette\Database\Table\IRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;

/**
 * Třída poskytuje metody pro správu článků v RS
 * @package App\CoreModule\Module
 * @author matyas
 */
class ArticleManager extends BaseManager{
    /**
     * konstanty pro manipulaci s modelem
     */
    const 
            TABLE_NAME = 'article',
            COLUMN_ID = 'article_id',
            COLUMN_URL = 'url';
    
    /**
     * Vrátí seznam článků v databázi
     * @return Selection seznam článků
     */
    public function getArticles(){
        return $this->database->table(self::TABLE_NAME)->order(self::COLUMN_ID . " " . 'DESC');
    }

    /**
     * vrátí článek z databáze podle jeho URL
     * @param string $url URL článku
     * @return bool|mixed|IRow první článek, který odpovídá URL nebo false při neúspěchu
     */
    
    public function getArticle(string $url){
        return $this->database->table(self::TABLE_NAME)->where(self::COLUMN_URL, $url)->fetch();
    }
    
    /**
     * Uloží článek do systému. Pokud není nastaveno ID, vloží nový, jinak provede editaci.
     * @param array|ArrayHash $article
     */
    public function saveArticle(array $article){
        if (!$article[self::COLUMN_ID])
            $this->database->table(self::TABLE_NAME)->insert($article);
        else
            $this->database->table(self::TABLE_NAME)->where(self::COLUMN_URL, $article[self::COLUMN_ID])->update($article);
    }
    
    /**
     * Odstraní článek
     * @param string $url URL článku
     */
    public function deleteArticle(string $url){
        $this->database->table(self::TABLE_NAME)->where(self::COLUMN_URL, $url)->delete();
    }
}

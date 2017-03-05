<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CoreModule\Presenters;

use App\Presenters\BasePresenter;

/**
 * Zpracovává vykreslování článků
 * @package App\CoreModule\Presenters
 * @author matyas
 */
class ArticlePresenter extends BasePresenter
{
    /** Konstanta s hodnotou URL výchozího článku */
    const DEFAULT_ARTICLE_URL = 'uvod';
    protected $presenter;

    public function startup()
    {
        parent::startup();
        $this->entityManager = $this->articleManager;
        $this->presenter = ':Article:';
    }

    /**
     * Načte a vykreslí článek do šablony podle jeho URL
     * @param string $url URL článku
     */
    public function renderDefault(string $url = NULL)
    {
        if (!$url)
            $url = self::DEFAULT_ARTICLE_URL;

        //Pokusí se načíst článek s danou URL a pokud nebude nalezen, vyhodí chybu 404
        if (!($article = $this->entityManager->getEntityByUnique($url))) {
            $article = $this->entityManager->getEntityByUnique('chyba');
            $this->template->originalUrl = $url;
        }
        if (!($this->user->isInRole('admin') || $article->requestable)) {
            $article = $this->entityManager->getEntityByUnique('chyba');
        }
        $this->template->article = $article; //Předá článek do šablony
    }

    /**
     * Načte články z databáze
     */
    public function actionList()
    {
        //Načte články z databáze a předá je šabloně
        $articles = $this->entityManager->getEntities();
        $this->template->articles = $articles;
    }
}

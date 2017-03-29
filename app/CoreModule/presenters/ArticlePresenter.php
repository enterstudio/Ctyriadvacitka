<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CoreModule\Presenters;

use app\CoreModule\model\TableRow;
use App\Presenters\BasePresenter;
use Nette\Utils\Strings;

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
        if (!($this->user->isInRole('admin') || $this->user->isInRole('editor') || $article->requestable)) {
            $article = $this->entityManager->getEntityByUnique('chyba');
        }
        $this->template->article = $article; //Předá článek do šablony
    }

    /**
     * Renders list of news on pages. There are 4 news in ine page
     * @param int $page page of list
     * @throws \Nette\Application\AbortException
     */
    public function renderPagedList(int $page = 1)
    {
        $news = $this->entityManager->getEntities()->where('requestable', 1);
        $offset = ($page - 1) * 4;
        $pages = ceil($news->count() / 4);

        if ($page > $pages) {
            $this->flashMessage('Tolik stránek tu nemáme.', 'warning');
            $this->redirect(':Core' . $this->presenter . 'pagedList');
        }

        $news = $news->limit(4, $offset);
        $tableRows = [];

        foreach ($news as $new) {
            $tableRow = new TableRow($new, $this->entityManager->getColumns());
            $tableRow->setContent(Strings::truncate($tableRow->getContent(), 80));
            $tableRows[] = $tableRow;
        }

        $this->template->articles = $tableRows;
        $this->template->activePage = $page;
        $this->template->pages = $pages;
    }
}

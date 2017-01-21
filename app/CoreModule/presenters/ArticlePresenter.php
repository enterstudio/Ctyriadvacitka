<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CoreModule\Presenters;

use App\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;

/**
 * Zpracovává vykreslování článků
 * @package App\CoreModule\Presenters
 * @author matyas
 */
class ArticlePresenter extends BasePresenter{
    /** Konstanta s hodnotou URL výchozího článku */
    const DEFAULT_ARTICLE_URL = 'uvod';

    public function startup()
    {
        parent::startup();
        $this->resourceManager = $this->articleManager;
    }

    /**
     * Načte a vykreslí článek do šablony podle jeho URL
     * @param string $url URL článku
     */
    public function renderDefault(string $url = NULL){
        if (!$url)
            $url = self::DEFAULT_ARTICLE_URL;
        
        //Pokusí se načíst článek s danou URL a pokud nebude nalezen, vyhodí chybu 404
        if (!($article = $this->resourceManager->getArticle($url)))
            $article = $this->resourceManager->getArticle('chyba');
        $this->template->article = $article; //Předá článek do šablony
    }

    /**
     * Odstraní článek
     * @param string $url
     */
    public function actionRemove(string $url){
        if (!$this->user->isLoggedIn()){
            $this->flashMessage('Nejste přihlášen!');
            $this->redirect(':Core:Session:signIn');
        }
        if (!$this->user->isAllowed('article', 'edit')){
            $this->flashMessage('Nemůžete mazat články!');
            $this->redirect(':Core:Article:', $url);
        }
        $this->resourceManager->deleteArticle($url);
        $this->flashMessage('Článek byl úspěšně odstraněn.');
        $this->redirect(':Core:Article:list');
    }

    /**
     * Vykresluje editaci článku podle jeho URL
     * @param string $url URL adresa článku, který editujeme, pokud není zadána, vytoří se nový
     */
    public function actionEditor(string $url = NULL){
        //Pokud byla zadána URL, pokusí se článek načíst a předat jeho hodnoty do editačního formuláře, jinak vypíše chybovou hlášku
        if ($url) ($article = $this->resourceManager->getArticle($url)) ? $this['editorForm']->setDefaults($article) : $this->flashMessage('Článek nebyl nalezen');
        if (!$this->user->isLoggedIn()){
            $this->flashMessage('Nejste přihlášen!');
            $this->redirect(':Core:Session:signIn');
        }
        if (!$this->user->isAllowed('article', 'edit'));{
            $this->flashMessage('Nemůžete upravovat články!');
            $this->redirect(':Core:Article:', $url);
        }
    }

    /**
     * Načte články z databáze
     */
    public function actionList(){
        //Načte články z databáze a předá je šabloně
        $articles = $this->resourceManager->getArticles();
        $this->template->articles = $articles;
    }

    /**
     * Vrátí formulář pro editor článků.
     * @return Form formulář pro editor článků
     */
    protected function createComponentEditorForm():Form{
        $form = new Form();
        $form->addHidden('article_id');
        $form->addText('title', 'Titilek')->setRequired();
        $form->addText('url', 'URL')->setRequired();
        $form->addText('description', 'Popisek')->setRequired();
        $form->addTextArea('content', 'Obsah')->setRequired();
        $form->addSubmit('submit', 'Uložit článek');
        $form->onSuccess[] = [$this,'editorFormSucceeded'];
        return $form;

    }

    /**
     * Funkce se vykoná při úspěšném odeslání formuláře; zpracuje hodnoty formuláře.
     * @param Form $form formulář editoru
     * @param ArrayHash $values odeslané hodnoty formuláře
     */
    public function editorFormSucceeded($form, array $values){
        try{
            $this->resourceManager->saveArticle($values);
            $this->flashMessage('Článek byl úspěšně uložen.');
            $this->redirect(":Core:Article:", $values['url']);
        }
        catch (UniqueConstraintViolationException $exception){
            $this->flashMessage('Článek s touto URL adresou již existuje.');
        }
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CoreModule\Presenters;

use App\Presenters\BasePresenter;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Forms\Form;
use Nette\Utils\ArrayHash;

/**
 * Zpracovává vykreslování článků
 * @package App\CoreModule\Presenters
 * @author matyas
 */
class ArticlePresenter extends BasePresenter{
    /** Konstanta s hodnotou URL výchozího článku */
    const DEFAULT_ARTICLE_URL = 'uvod';
    protected $presenter;

    public function startup()
    {
        parent::startup();
        $this->entityManager = $this->articleManager;
        $this->presenter = ':Core:Article:';
    }

    /**
     * Načte a vykreslí článek do šablony podle jeho URL
     * @param string $url URL článku
     */
    public function renderDefault(string $url = NULL){
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
     * Odstraní článek
     * @param string $url
     */
    public function actionRemove(string $url){
        if (!$this->user->isLoggedIn()){
            $this->flashMessage('Nejste přihlášen!', 'warning');
            $this->redirect(':Core:Session:signIn');
        }
        if (!$this->user->isAllowed('article', 'edit')){
            $this->flashMessage('Nemůžete mazat články!', 'danger');
            $this->redirect($this->presenter, $url);
        }
        $this->entityManager->deleteEntity($url);
        $this->flashMessage('Článek byl úspěšně odstraněn.', 'success');
        $this->redirect($this->presenter . 'list');
    }

    /**
     * Vykresluje editaci článku podle jeho URL
     * @param string $url URL adresa článku, který editujeme, pokud není zadána, vytoří se nový
     */
    public function actionEditor(string $url = NULL){
        //Pokud byla zadána URL, pokusí se článek načíst a předat jeho hodnoty do editačního formuláře, jinak vypíše chybovou hlášku
        if ($url && $article = $this->entityManager->getEntityByUnique($url)) {
            $this['editorForm']->setDefaults($article);
        } else if ($url) {
            $this->flashMessage('Článek nebyl nalezen, bude vytvořen nový.', 'info');
            $article = new ArrayHash();
            $article->url = $url;
            $this['editorForm']->setDefaults($article);
        }
        if (!$this->user->isLoggedIn()){
            $this->flashMessage('Nejste přihlášen!', 'warning');
            $this->redirect(':Core:Session:signIn');
        }
        if (!$this->user->isAllowed('article', 'edit')){
            $this->flashMessage('Nemůžete upravovat články!', 'danger');
            $this->redirect($this->presenter, $url);
        }
    }

    /**
     * Načte články z databáze
     */
    public function actionList(){
        //Načte články z databáze a předá je šabloně
        $articles = $this->entityManager->getEntities();
        $this->template->articles = $articles;
    }

    /**
     * Vrátí formulář pro editor článků.
     * @return Form formulář pro editor článků
     */
    protected function createComponentEditorForm():Form{
        $form = $this->formFactory->create();
        $form->addHidden($this->entityManager->getPrimaryKey());
        $form->addText('title', 'Titilek')->setRequired();
        $form->addText('url', 'URL')->setRequired();
        $form->addText('description', 'Popisek')->setRequired();
        $form->addCheckbox('requestable', 'Zobrazovat v seznamu');
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
            $this->entityManager->saveEntity($values);
            $this->flashMessage('Článek byl úspěšně uložen.', 'success');
            $this->redirect($this->presenter, $values['url']);
        }
        catch (UniqueConstraintViolationException $exception){
            $this->flashMessage('Článek s touto URL adresou již existuje.', 'warning');
        }
    }
}

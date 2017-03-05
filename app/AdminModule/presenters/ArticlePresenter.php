<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 5.3.17
 * Time: 12:05
 */

namespace App\AdminModule\Presenters;


use App\Presenters\BasePresenter;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Forms\Form;
use Nette\Utils\ArrayHash;

/**
 * Class ArticlePresenter
 * @package App\AdminModule\Presenters
 */
class ArticlePresenter extends BasePresenter
{
    protected $presenter;

    public function startup()
    {
        parent::startup();
        $this->entityManager = $this->articleManager;
        $this->presenter = ':Article:';

        $this->logInRequired();
        $this->editorPermissionsRequired();
    }

    /**
     * Odstraní článek
     * @param string $url
     */
    public function actionRemove(string $url)
    {
        $this->entityManager->deleteEntity($url);
        $this->flashMessage('Článek byl úspěšně odstraněn.', 'success');
        $this->redirect(':Core' . $this->presenter . 'list');
    }

    /**
     * Vykresluje editaci článku podle jeho URL
     * @param string $url URL adresa článku, který editujeme, pokud není zadána, vytoří se nový
     */
    public function renderEditor(string $url = NULL)
    {
        //Pokud byla zadána URL, pokusí se článek načíst a předat jeho hodnoty do editačního formuláře, jinak vypíše chybovou hlášku
        if ($url && $article = $this->entityManager->getEntityByUnique($url)) {
            $this['editorForm']->setDefaults($article);
        } else if ($url) {
            $this->flashMessage('Článek nebyl nalezen, bude vytvořen nový.', 'info');
            $article = new ArrayHash();
            $article->url = $url;
            $this['editorForm']->setDefaults($article);
        }
    }

    /**
     * Vrátí formulář pro editor článků.
     * @return Form formulář pro editor článků
     */
    protected function createComponentEditorForm(): Form
    {
        $form = $this->formFactory->create();
        $form->addHidden($this->entityManager->getPrimaryKey());
        $form->addText('title', 'Titilek')->setRequired();
        $form->addText('url', 'URL')->setRequired();
        $form->addText('description', 'Popisek')->setRequired();
        $form->addCheckbox('requestable', 'Zobrazovat v seznamu');
        $form->addTextArea('content', 'Obsah')->setRequired();
        $form->addSubmit('submit', 'Uložit článek');
        $form->onSuccess[] = [$this, 'editorFormSucceeded'];
        return $form;

    }

    /**
     * Funkce se vykoná při úspěšném odeslání formuláře; zpracuje hodnoty formuláře.
     * @param Form $form formulář editoru
     * @param array|ArrayHash $values odeslané hodnoty formuláře
     */
    public function editorFormSucceeded($form, array $values)
    {
        try {
            $this->entityManager->saveEntity($values);
            $this->flashMessage('Článek byl úspěšně uložen.', 'success');
            $this->redirect(':Core' . $this->presenter, $values['url']);
        } catch (UniqueConstraintViolationException $exception) {
            $this->flashMessage('Článek s touto URL adresou již existuje.', 'warning');
        }
    }
}
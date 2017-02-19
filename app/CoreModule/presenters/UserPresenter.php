<?php
/**
 * Created by PhpStorm.
 * User: matyas
 * Date: 4.11.16
 * Time: 18:39
 */

namespace App\CoreModule\Presenters;


use App\Presenters\BasePresenter;
use Nette\Forms\Form;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;

/**
 * Class UserPresenter zpracovává vykreslení uživatele
 * @package App\CoreModule\Presenters
 * $author matyas
 */
class UserPresenter extends BasePresenter
{

    /**
     * Načte a vykreslí uživatele do šablony podle username
     * @param string|null $username jméno uživatele
     */
    public function renderDefault(string $username = null)
    {
        if (!$username) {
            if ($this->user->isLoggedIn()) {
                $this->redirect(':Core:User:', $this->user->getIdentity()->username);
            } else {
                $this->redirect(':Core:Article:default');
            }
        }
        //Pokusí se najít uživatele s daným jménem, pokud nebude nalezen, vyhodí chybu 404
        if (!($user = $this->userManager->getEntityByUnique($username))) {
            $this->flashMessage('Uživatel nebyl nalezen.', 'warning');
            $this->redirect(':Core:Article:');
        }
        $this->template->user = $user;

    }

    /**
     * Odstraní uživatele
     * @param string $username jméno uživatele
     */
    public function actionRemove(string $username)
    {
        $this->logInRequired();
        if (!$this->user->isAllowed('user', 'remove')) {
            $this->flashMessage('Nemůžete mazat uživatele!', 'danger');
            $this->redirect(':Core:User:', $username);
        }
        $this->userManager->deleteEntity($username);
        $this->flashMessage('Uživatel byl úspěšně odstraněn.', 'success');
        $this->redirect(':Core:User:list');
    }

    /**
     * Vykresluje editaci uživatele podle jeho username
     * @param string $username jméno uživatele, kterého editujeme
     */
    public function actionEditor(string $username = NULL)
    {
        $this->logInRequired();
        //Pokud bylo zadáno jméno, pokusí se uživatele načíst a předat jeho hodnoty do editačního formuláře, jinak vypíše chybovou hlášku
        if ($username) ($user = $this->userManager->getEntityByUnique($username)) ? $this['userEditorForm']->setDefaults($user) : $this->flashMessage('Uživatel nebyl nalezen');

        //Pokud je přihášen uživatel, ale nevyplní paremetr username, bude editovat sebe
        if (!$username && $this->user->isLoggedIn()) {
            $user = $this->userManager->getEntityByPrimaryKey($this->user->getId());
            $this['userEditorForm']->setDefaults($user);
        }

        if ($this->user->id != $user['user_id']) {
            $this->flashMessage('Nemůžete upravovat jiné uživatele!', 'danger');
            $this->redirect(':Core:User:', $this->user->getIdentity()->username);
        }
        $this->template->user = $user;
    }

    /**
     * Načte uživatele z databáze
     */
    public function actionList()
    {
        //Načte uživatele z databáze a předá je šabloně
        $users = $this->userManager->getEntities();
        $this->template->users = $users;
    }

    /**
     * Vrátí formulář pro editor článků.
     * @return Form formulář pro editor článků
     */
    protected function createComponentUserEditorForm(): Form
    {
        $form = $this->formFactory->create();
        $form->addHidden('user_id');
        $form->addText('name', 'Jméno');
        $form->addText('surname', 'Příjmení');
        $form->addText('nickname', 'Přezdívka');
        $form->addText('username', 'Přihlašovací jméno')->setRequired();
        $form->addEmail('email', 'E-mail')->setRequired();
        $form->addHidden('role');
        $form->addSubmit('submit', 'Uložit');
        $form->onSuccess[] = [$this, 'userEditorFormSucceeded'];
        return $form;

    }

    /**
     * Funkce se vykoná při úspěšném odeslání formuláře; zpracuje hodnoty formuláře.
     * @param Form $form formulář editoru
     * @param ArrayHash $values odeslané hodnoty formuláře
     */
    public function userEditorFormSucceeded($form, array $values)
    {
        try {
            $this->userManager->saveEntity($values);
            $this->flashMessage('Uživatel byl úspěšně editován.', 'success');
            $this->redirect(':Core:User:', $values['username']);
        } catch (UniqueConstraintViolationException $exception) {
            $this->flashMessage('Uživatel s tímto jménem již existuje.', 'warning');
        }
    }

    /**
     * Changes password of user
     * @param $username
     */
    public function renderChangePassword($username)
    {
        $this->logInRequired();
        if ($this->user->getIdentity()->username != $username) {
            $this->flashMessage('Změna hesla je možná jen u sebe.');
            $this->redirect(':Core:User:', $username);
        }
    }

    /**
     * Creates form for changing password of logged user
     * @return Form
     */
    public function createComponentChangePasswordForm(): Form
    {
        $form = $this->formFactory->create();
        $form->addPassword('currentPassword', 'Aktuální heslo')
            ->setRequired();
        $form->addPassword('newPassword', 'Nové heslo')
            ->setRequired();
        $form->addPassword('newPasswordAgain', 'Nové heslo znovu')
            ->setRequired()
            ->addRule(Form::EQUAL, 'Hesla se neschodují!', $form['newPassword']);
        $form->addSubmit('submit', 'Změnit heslo');
        $form->onSuccess[] = [$this, 'changePasswordFormSucceeded'];
        return $form;
    }

    /**
     * @param Form $form succeeded form
     * @param array $values values of form
     */
    public function changePasswordFormSucceeded(Form $form, array $values)
    {
        try {
            $username = $this->user->getIdentity()->username;
            $this->userManager->changePassword($username, $values['currentPassword'], $values['newPassword']);
            $this->flashMessage('Heslo bylo úspěšně změněno.', 'success');
            $this->redirect(':Core:User:');
        } catch (AuthenticationException $e) {
            $this->flashMessage($e->getMessage(), 'danger');
        }
    }
}

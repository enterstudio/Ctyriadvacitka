<?php
/**
 * Created by PhpStorm.
 * User: matyas
 * Date: 4.11.16
 * Time: 18:39
 */

namespace App\CoreModule\Presenters;


use App\CoreModule\Model\UserManager;
use App\Presenters\BasePresenter;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;

/**
 * Class UserPresenter zpracovává vykreslení uživatele
 * @package App\CoreModule\Presenters
 * $author matyas
 */
class UserPresenter extends BasePresenter {

    /** @var  UserManager Instance třídy UserManager pro práci s uživateli */
    protected $userManager;

    /**
     * UserPresenter constructor.
     * @param UserManager $userManager automaticky injectovaná třída modelu pro práci s uživateli
     */
    public function __construct(UserManager $userManager){
        parent::__construct();
        $this->userManager = $userManager;
    }

    /**
     * Načte a vykreslí uživatele do šablony podle username
     * @param string|null $username jméno uživatele
     * @throws BadRequestException jestliže uživatel s daným username nebyl nalezen
     */
    public function renderDefault(string $username = null){
        if (!$username)
            $this->redirect(':Core:Article:default');
        //Pokusí se najít uživatele s daným jménem, pokud nebude nalezen, vyhodí chybu 404
        if (!($user = $this->userManager->getUserByUsername($username))){
            throw new BadRequestException();
        }
        $this->template->user = $user;

    }

    /**
     * Odstraní uživatele
     * @param string $username jméno uživatele
     */
    public function actionRemove(string $username){
        $this->userManager->removeUser($username);
        $this->flashMessage('Uživatel byl úspěšně odstraněn.');
        $this->redirect(':Core:User:list');
    }

    /**
     * Vykresluje editaci uživatele podle jeho username
     * @param string $username jméno uživatele, kterého editujeme
     */
    public function actionEditor(string $username = NULL){
        //Pokud bylo zadáno jméno, pokusí se uživatele načíst a předat jeho hodnoty do editačního formuláře, jinak vypíše chybovou hlášku
        if ($username) ($user = $this->userManager->getUserByUsername($username)) ? $this['editorForm']->setDefaults($user) : $this->flashMessage('Uživatel nebyl nalezen');
        $this->template->user = $user;
    }

    /**
     * Načte uživatele z databáze
     */
    public function actionList(){
        //Načte uživatele z databáze a předá je šabloně
        $users = $this->userManager->getUsers();
        $this->template->users = $users;
    }

    /**
     * Vrátí formulář pro editor článků.
     * @return Form formulář pro editor článků
     */
    protected function createComponentEditorForm():Form{
        $form = new Form();
        $form->addHidden('user_id');
        $form->addText('username', 'Přihlašovací jméno')->setRequired();
        $form->addPassword('password', 'Heslo')->setRequired();
        $form->addHidden('role');
        $form->addSubmit('submit', 'Uložit');
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
            $values['password'] = Passwords::hash($values['password']);
            $this->userManager->saveUser($values);
            $this->flashMessage('Uživatel byl úspěšně editován.');
            $this->redirect(':Core:User:', $values['username']);
        }
        catch (UniqueConstraintViolationException $exception){
            $this->flashMessage('Článek s touto URL adresou již existuje.');
        }
    }

}
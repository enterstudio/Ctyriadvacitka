<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 19.11.16
 * Time: 22:04
 */

namespace App\CoreModule\Presenters;


use App\CoreModule\Model\AuthenticatorManager;
use App\CoreModule\Model\UserManager;
use App\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 * Class SessionPresenter zpracovává registraci a přihlášení uživatele
 * @package App\CoreModule\Presenters
 * @author matyas
 */
class SessionPresenter extends BasePresenter {

    /** @var userManager Instance třídy pro práci s uživateli */
    protected $userManager;
    protected $authenticator;
    protected $user;

    /**
     * SessionPresenter constructor.
     * @param UserManager $userManager automaticky injectovaná třída pro práci s uživateli
     */
    public function __construct(UserManager $userManager, AuthenticatorManager $authenticatorManager){
        parent::__construct();
        $this->userManager = $userManager;
        $this->authenticator = $authenticatorManager;
    }

    /**
     * Přihlásí uživatele, pokud je někdo přihlášen, přesměruje ho na jeho profil
     */
    public function actionSingIn(){
        $this->isLoggedUser();
    }

    /**
     * Formulář k přihlášení
     * @return Form přihlašovací formulář
     */
    public function createComponentSingInForm():Form{
        $form = new Form();
        $form->addText('username', 'Přihlašovací jméno')->setRequired();
        $form->addPassword('password', 'Heslo')->setRequired();
        $form->addSubmit('submit', 'Přihlásit');
        $form->onSuccess[] = [$this, 'singInFormSucceeded'];
        return $form;
    }

    /**
     * @param $form instance formuláře
     * @param ArrayHash $values data z formuláře
     */
    public function singInFormSucceeded($form, ArrayHash $values){
        $this->user = $this->getUser();
        $this->user->setAuthenticator(new AuthenticatorManager($this->userManager->getDatabase()));
        $this->user->login($values['username'], $values['password']);
        $this->flashMessage('Přihlášení proběhlo úspěšně.');
        $this->redirectUrl('profil/' . $values['username']);
    }

    /**
     * Pokud je přihlášen uživatel, přesměruje na jeho profil
     */
    public function isLoggedUser(){
        $this->user = $this->getUser();
        if ($this->user->isLoggedIn()){
            $this->redirectURL('profil/' . $this->userManager->getUserByID($this->user->getId())->username);
        }
    }
}
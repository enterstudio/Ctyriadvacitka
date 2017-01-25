<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 19.11.16
 * Time: 22:04
 */

namespace App\CoreModule\Presenters;


use App\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;

/**
 * Class SessionPresenter zpracovává registraci a přihlášení uživatele
 * @package App\CoreModule\Presenters
 * @author matyas
 */
class SessionPresenter extends BasePresenter {

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
    public function createComponentSignInForm():Form{
        $form = new Form();
        $form->addText('username', 'Přihlašovací jméno')->setRequired();
        $form->addPassword('password', 'Heslo')->setRequired();
        $form->addSubmit('submit', 'Přihlásit');
        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }

    /**
     * @param $form instance formuláře
     * @param ArrayHash $values data z formuláře
     */
    public function signInFormSucceeded($form, ArrayHash $values){
        try {
            $this->user->login($values['username'], $values['password']);
            $this->flashMessage('Přihlášení proběhlo úspěšně.');
            $this->redirectUrl('profil/' . $values['username']);
        }
        catch (AuthenticationException $e){
            $this->flashMessage($e->getMessage());
        }
    }

    /**
     * Odhlásí uživatele
     */
    public function actionSignOut(){
        $this->setLayout(false);
        if (!$this->user->isLoggedIn()){
            $this->flashMessage('Není přihlášen žádný uživatel.');
            $this->redirect(':Core:Article:');
        }
        else{
            $this->user->logout(true);
            $this->flashMessage('Uživatel úspěšně odhlášen.');
            $this->redirect(':Core:Article:');
        }
    }

    /**
     * Zaregistruje nového uživatele
     */
    public function actionSignUp(){
    }

    /**
     * Vytvoří komponentu registrovacího formuláře
     * @return Form formulář k registraci
     */
    public function createComponentSignUpForm():Form{
        $form = new Form();
        $form->addText('username', 'Přihlašovací jméno')->setRequired();
        $form->addPassword('password', 'Heslo')->setRequired();
        $form->addPassword('password_again', 'Heslo znovu')->setRequired()
            ->addRule(Form::EQUAL, 'Hesla se neschodují!', $form['password']);
        $form->addSubmit('submit', 'Registrovat');
        $form->onSuccess[] = [$this, 'signUpFormSucceeded'];
        return $form;
    }

    /**
     * @param $form instance formuláře
     * @param ArrayHash $values hodnoty z formuláře
     */
    public function signUpFormSucceeded($form, ArrayHash $values){
        $username = $values['username'];
        $password = Passwords::hash($values['password']);
        $user = array($username, $password, 'registered');
        if ($this->userManager->userExists($username)){
            $this->flashMessage('Uživatel s tímto přihlašovacím jménem již existuje.');
        }
        else{
            $this->userManager->saveUser($user);
            $this->flashMessage('Registrace proběhla úspěšně.');
            $this->redirect(':Core:Session:signIn');
        }

    }

    /**
     * Pokud je přihlášen uživatel, přesměruje na jeho profil
     */
    public function isLoggedUser(){
        if ($this->user->isLoggedIn()){
            $this->redirectURL('profil/' . $this->userManager->getUserByID($this->user->getId())->username);
        }
    }
}
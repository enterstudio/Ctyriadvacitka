<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 19.11.16
 * Time: 22:04
 */

namespace App\CoreModule\Presenters;


use App\Presenters\BasePresenter;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Forms\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;

/**
 * Class SessionPresenter zpracovává registraci a přihlášení uživatele
 * @package App\CoreModule\Presenters
 * @author matyas
 */
class SessionPresenter extends BasePresenter
{

    /**
     * Přihlásí uživatele, pokud je někdo přihlášen, přesměruje ho na jeho profil
     * @throws \Nette\Application\AbortException
     */
    public function renderSignIn()
    {
        $this->redirectIfLoggedUser();
    }

    /**
     * Formulář k přihlášení
     * @return Form přihlašovací formulář
     */
    public function createComponentSignInForm(): Form
    {
        $form = $this->formFactory->create();
        $form->addText('username', 'Přihlašovací jméno')->setRequired();
        $form->addPassword('password', 'Heslo')->setRequired();
        $form->addSubmit('submit', 'Přihlásit');
        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }

    /**
     * @param $form instance formuláře
     * @param ArrayHash $values data z formuláře
     * @throws \Nette\Application\AbortException
     */
    public function signInFormSucceeded($form, ArrayHash $values)
    {
        try {
            $this->user->login($values['username'], $values['password']);
            $this->flashMessage('Přihlášení proběhlo úspěšně.', 'success');
            $this->redirect(':Core:User:', $values['username']);
        } catch (AuthenticationException $e) {
            $this->flashMessage($e->getMessage(), 'warning');
        }
    }

    /**
     * Odhlásí uživatele
     * @throws \Nette\Application\AbortException
     */
    public function actionSignOut()
    {
        $this->setLayout(false);
        if (!$this->user->isLoggedIn()) {
            $this->flashMessage('Není přihlášen žádný uživatel.', 'info');
            $this->redirect(':Core:Article:');
        } else {
            $this->user->logout(true);
            $this->flashMessage('Uživatel úspěšně odhlášen.', 'success');
            $this->redirect(':Core:Article:');
        }
    }

    /**
     * Zaregistruje nového uživatele
     * @throws \Nette\Application\AbortException
     */
    public function actionSignUp()
    {
        $this->redirectIfLoggedUser();
    }

    /**
     * Vytvoří komponentu registrovacího formuláře
     * @return Form formulář k registraci
     */
    public function createComponentSignUpForm(): Form
    {
        $form = $this->formFactory->create();
        $form->addText('username', 'Přihlašovací jméno')->setRequired();
        $form->addPassword('password', 'Heslo')->setRequired();
        $form->addPassword('passwordAgain', 'Heslo znovu')->setRequired()
            ->addRule(Form::EQUAL, 'Hesla se neschodují!', $form['password']);
        $form->addEmail('email', 'E-mail')->setRequired();
        $form->addSubmit('submit', 'Registrovat');
        $form->onSuccess[] = [$this, 'signUpFormSucceeded'];
        return $form;
    }

    /**
     * @param $form instance formuláře
     * @param ArrayHash $values hodnoty z formuláře
     * @throws \Nette\Application\AbortException
     */
    public function signUpFormSucceeded($form, ArrayHash $values)
    {
        $username = $values['username'];
        $password = Passwords::hash($values['password']);
        $user = array(
            'username' => $username,
            'password' => $password,
            'role' => 'registered',
            'email' => $values['email']);
        try {
            $this->userManager->saveEntity($user);
            $this->flashMessage('Registrace proběhla úspěšně, nyní se přihlašte.', 'success');
            $this->redirect(':Core:Session:signIn');
        } catch (UniqueConstraintViolationException $e) {
            $this->flashMessage('Uživatel s tímto přihlašovacím jménem nebo e-mailem již existuje.', 'warning');
        }

    }

    /**
     * It will redirect to user profile if user is logged in
     * @throws \Nette\Application\AbortException
     */
    public function redirectIfLoggedUser()
    {
        if ($this->user->isLoggedIn()) {
            $username = $this->user->getIdentity()->username;
            $this->flashMessage("Už je přihlášen uživatel $username.", 'info');
            $this->redirect(':Core:User:', $username);
        }
    }
}

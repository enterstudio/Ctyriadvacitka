<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 19.11.16
 * Time: 22:04
 */

namespace App\CoreModule\Presenters;


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

    /**
     * SessionPresenter constructor.
     * @param UserManager $userManager automaticky injectovaná třída pro práci s uživateli
     */
    public function __construct(UserManager $userManager){
        parent::__construct();
        $this->userManager = $userManager;
    }

    /**
     * Přihlásí uživatele, pokud je někdo přihlášen, přesměruje ho na jeho profil
     */
    public function actionSingIn(){
        if (!empty($_SESSION['username'])){
            $this->redirectUrl('/profil/' . $_SESSION['username']);
        }
    }

    /**
     * Formulář k přihlášení
     * @return Form přihlašovací formulář
     */
    public function createComponentSingInForm():Form{
        $form = new Form();
        $form->addText('username', 'Přihlašovací jméno')->setRequired();
        $form->addText('password', 'Heslo')->setRequired();
        $form->addSubmit('submit', 'Přihlásit');
        $form->onSuccess[] = [$this, 'singInFormSucceeded'];
        return $form;
    }

    public function singInFormSucceeded($form, ArrayHash $values){
        $passwordIsCorrect = ($values['password'] == $this->userManager->getUserByUsername($values['username'])->password);

        if ($passwordIsCorrect) {
            $this->userManager->singInUser($values['username']);
            $this->flashMessage('Byl jste úspěšně přihlášen.');
            $this->redirectUrl('/profil/' . $values['username']);
        }
        else{
            $this->flashMessage('Nesprávné heslo.');
        }
    }
}
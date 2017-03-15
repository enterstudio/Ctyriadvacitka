<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 22.2.17
 * Time: 14:54
 */

namespace App\InstallModule\Presenters;


use App\Factories\HeaderImagesForm;
use App\Presenters\BasePresenter;
use Nette\Forms\Form;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;

/**
 * Class InstallPresenter
 * @package App\InstallModule\Presenters
 */
class InstallPresenter extends BasePresenter
{
    /** @var  HeaderImagesForm @inject */
    public $headerImagesFormFactory;

    public function startup()
    {
        parent::startup();
        $this->checkInstallation();
    }

    /**
     * @return Form
     */
    public function createComponentProjectPropertiesForm(): Form
    {
        $form = $this->formFactory->create();
        $form->addText('name', 'Jméno stránek');
        $form->addText('description', 'Popis stránek');
        $form->addSubmit('submit', 'Uložit');
        $form->onSuccess[] = [$this, 'projectPropertiesFormSucceeded'];
        return $form;
    }

    /**
     * @param Form $form
     * @param ArrayHash $values
     */
    public function projectPropertiesFormSucceeded(Form $form, ArrayHash $values)
    {
        $this->projectManager->saveParameter('webName', $values['name']);
        $this->projectManager->saveParameter('webDescription', $values['description']);
        $this->redirect(':Install:Install:adminAccount');
    }

    /**
     * @return Form
     */
    public function createComponentAdminAccountForm(): Form
    {
        $form = $this->formFactory->create();
        $form->addText('username', 'Uživatelské jméno')
            ->setRequired();
        $form->addPassword('password', 'Heslo')
            ->setRequired();
        $form->addPassword('passwordAgain', 'Heslo znovu')
            ->addRule(Form::EQUAL, 'Hesla se neschodují', $form['password'])
            ->setRequired();
        $form->addSubmit('submit', 'Uložit');
        $form->onSuccess[] = [$this, 'adminAccountFormSucceeded'];
        return $form;
    }

    /**
     * @param Form $form
     * @param array $values
     */
    public function adminAccountFormSucceeded(Form $form, array $values)
    {
        $username = $values['username'];
        $password = Passwords::hash($values['password']);
        $user = array(
            'username' => $username,
            'password' => $password,
            'role' => 'admin');
        $this->userManager->saveEntity($user);
        $this->redirect(':Install:Install:headerImages');
    }

    /**
     * @return Form
     */
    public function createComponentAddHeaderImagesForm(): Form
    {
        $form = $this->headerImagesFormFactory->create();
        $form->onSuccess[] = function (Form $form) {
            $this->projectManager->saveParameter('installationCompleted', true);
            $this->redirect(':Core:Article:');
        };
        return $form;
    }

    public function checkInstallation()
    {
        if ($this->projectManager->getParameter('installationCompleted')) {
            $this->flashMessage('Instalace již proběhla', 'warning');
            $this->redirect(':Core:Article:');
        }
    }
}
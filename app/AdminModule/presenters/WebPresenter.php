<?php
/**
 * Created by PhpStorm.
 * User: matyas
 * Date: 9.10.16
 * Time: 11:59
 */

namespace App\AdminModule\Presenters;


use App\Presenters\BasePresenter;
use Nette\Forms\Form;

/**
 * Zpracovává vykreslování administrační sekce
 * Class AdministrationPresenter
 * @package App\CoreModule\Presenters
 */
class WebPresenter extends BasePresenter
{
    public function startup()
    {
        parent::startup();
        $this->logInRequired();
    }

    public function renderDefault()
    {
        $this->adminPermissionsRequired();
        $properties['webName'] = $this->projectManager->getParameter('webName');
        $properties['webDescription'] = $this->projectManager->getParameter('webDescription');
        $this['webPropertiesForm']->setDefaults($properties);
    }

    public function renderView()
    {
        $this->editorPermissionsRequired();
    }

    /**
     * @return Form
     */
    public function createComponentWebPropertiesForm(): Form
    {
        $form = $this->formFactory->create();
        $form->addText('webName', 'Jméno webu')
            ->setRequired();
        $form->addText('webDescription', 'Popis webu')
            ->setRequired();
        $form->addSubmit('submit', 'Uložit');
        $form->onSuccess[] = [$this, 'webPropertiesFormSucceeded'];
        return $form;
    }

    /**
     * @param Form $form
     * @param array $values
     */
    public function webPropertiesFormSucceeded(Form $form, array $values)
    {
        foreach ($values as $key => $value) {
            $this->projectManager->saveParameter($key, $value);
        }
        $this->flashMessage('Údaje byly upraveny', 'success');
    }
}

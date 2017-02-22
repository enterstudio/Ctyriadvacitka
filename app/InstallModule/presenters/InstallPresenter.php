<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 22.2.17
 * Time: 14:54
 */

namespace App\InstallModule\Presenters;


use App\Presenters\BasePresenter;
use Nette\Forms\Form;
use Nette\Utils\ArrayHash;

/**
 * Class InstallPresenter
 * @package App\InstallModule\Presenters
 */
class InstallPresenter extends BasePresenter
{
    public function renderProjectProperties()
    {

    }

    /**
     * @return Form
     */
    public function createComponentProjectPropertiesForm(): Form
    {
        $form = $this->formFactory->create();
        $form->addText('name', 'Jméno stránek');
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
        $this->projectManager->setProjectName($values->name);
        $this->redirect(':Core:Article:');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 15.3.17
 * Time: 17:27
 */

namespace App\AdminModule\Presenters;


use App\Factories\HeaderImagesForm;
use App\Presenters\BasePresenter;
use Nette\Forms\Form;

/**
 * Class HeaderImages
 * @package App\AdminModule\Presenters
 */
class HeaderImagesPresenter extends BasePresenter
{
    /** @var  HeaderImagesForm @inject */
    public $headerImagesFormFactory;

    public function startup()
    {
        parent::startup();
        $this->logInRequired();
        $this->adminPermissionsRequired();
    }

    public function renderDefault()
    {
        $this->template->headerImages = $this->imageManager->getHeaderImageNames();
    }

    /**
     * @param string $name
     */
    public function actionRemove(string $name)
    {
        if ($this->imageManager->deleteImage($_SERVER["DOCUMENT_ROOT"] . "/www/img/top/$name")) {
            $this->flashMessage('Obrázek odstraněn', 'success');
        } else {
            $this->flashMessage('Obrázek se nepodařilo odstranit', 'danger');
        }
        $this->redirect(':Admin:HeaderImages:');
    }

    /**
     * @return Form
     */
    public function createComponentAddHeaderImagesForm(): Form
    {
        $form = $this->headerImagesFormFactory->create();
        $form->onSuccess[] = function (Form $form) {
            $this->redirect('this');
        };
        return $form;
    }
}
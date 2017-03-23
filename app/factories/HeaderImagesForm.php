<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 14.3.17
 * Time: 18:31
 */

namespace App\Factories;


use App\Model\BaseManager;
use App\Model\DatabaseHelper;
use Instante\Bootstrap3Renderer\BootstrapFormFactory;
use Nette\Database\Context;
use Nette\Forms\Form;
use Nette\Http\FileUpload;

/**
 * Class HeaderImagesForm
 * @package App\Model
 */
class HeaderImagesForm extends BaseManager
{
    private $formFactory;

    /**
     * HeaderImagesForm constructor.
     * @param Context $database
     * @param DatabaseHelper $databaseHelper
     * @param BootstrapFormFactory $formFactory
     */
    public function __construct(Context $database, DatabaseHelper $databaseHelper, BootstrapFormFactory $formFactory)
    {
        parent::__construct($database, $databaseHelper);
        $this->formFactory = $formFactory;
    }

    /**
     * @return \Instante\Bootstrap3Renderer\IBootstrapRenderedForm|\Nette\Forms\Form
     */
    public function create(): Form
    {
        $form = $this->formFactory->create();
        $form->getElementPrototype()->setAttribute('class', 'ajax');

        $form->addMultiUpload('newPictures', 'Přidat nové obrázky (lze více najednou)')
            ->setRequired("Musíte vybrat nejméně 1 obrázek!")
            ->addRule(Form::IMAGE, 'Obrázek musí být ve formátu JPEG, PNG nebo GIF.')
            ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 2 MB', 2 * 1024 * 1024);

        $form->addSubmit('submit', 'Přidat');
        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        $images = $form->getValues()->newPictures;

        /** @var FileUpload $image */
        foreach ($images as $image) {
            $image->move(__DIR__ . '/../../www/img/top/' . $image->name);
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: matyas
 * Date: 9.10.16
 * Time: 12:48
 */

namespace App\CoreModule\Presenters;

use App\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\InvalidStateException;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Utils\ArrayHash;

/**
 * Zpracovává kontaktní formulář
 * Class ContactPresenter
 * @package App\CoreModule\Presenters
 */
class ContactPresenter extends BasePresenter {

    /**Email administrátora, na který se budou posílat emaily z kontaktního formuláře. */
    const EMAIL = 'kroupa.matyas@gmail.com';

    /**
     * Vytváří a vrací komponentu kontaktního formuláře
     * @return Form kontaktní formulář
     */
    protected function createComponentContactForm():Form{
        $form = $this->formFactory->create();
        $form->addText('email', 'Vaše e-mailová adresa')->setType('email')->setRequired();
        $form->addText('y', 'Zadejte aktuální rok')->setRequired()
            ->addRule(Form::EQUAL, 'Chybně vyplněný antispam.', date("Y"));
        $form->addTextArea('message', 'Zpráva')->setRequired()
            ->addRule(Form::EQUAL, 'Zpráva musí být minimálně %d znaků dlouhá.', 10);
        $form->addSubmit('submit', 'Odeslat');
        $form->onSuccess[] = [$this, 'contactFormSucceeded'];
        return $form;
    }

    /**
     * Funkce se vykoná při úspěšném odeslání kontaktního formuláře a odeěle e-mail.
     * @param Form $form kontaktní formulář
     * @param ArrayHash $values odeslané hodnoty formuláře
     * @throws \Nette\Application\AbortException
     */
    public function contactFormSucceeded(Form $form, ArrayHash $values){
        try{
            $mail = new Message();
            $mail->setFrom($values->email)
                ->addTo(self::EMAIL)
                ->setSubject('Email z BasicRS')
                ->setBody($values->message);
            $mailer = new SendmailMailer();
            $mailer->send($mail);
            $this->flashMessage('E-mail byl úspěšně odeslán.', 'success');
            $this->redirect('this');
        }
        catch (InvalidStateException $exception){
            $this->flashMessage('E-mail se nepodařilo odeslat.', 'danger');
        }
    }
}

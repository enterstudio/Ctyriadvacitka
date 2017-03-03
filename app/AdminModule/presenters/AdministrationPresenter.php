<?php
/**
 * Created by PhpStorm.
 * User: matyas
 * Date: 9.10.16
 * Time: 11:59
 */

namespace App\AdminModule\Presenters;


use App\Presenters\BasePresenter;

/**
 * Zpracovává vykreslování administrační sekce
 * Class AdministrationPresenter
 * @package App\CoreModule\Presenters
 */
class AdministrationPresenter extends BasePresenter
{
    public function startup()
    {
        parent::startup();
        $this->logInRequired();

    }
}

<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 3.3.17
 * Time: 20:42
 */

namespace App\AdminModule\Presenters;


use App\Presenters\BasePresenter;

/**
 * Class UserPresenter
 * @package App\AdminModule\Presenters
 */
class UserPresenter extends BasePresenter
{
    public function renderManagement()
    {
        $this->logInRequired();
        $this->adminPermissionsRequired();

        $users = $this->userManager->getEntities();
        $this->template->users = $users;
    }
}
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
    public function startup()
    {
        parent::startup();
        $this->logInRequired();
        $this->adminPermissionsRequired();
    }

    public function renderManagement()
    {
        $users = $this->userManager->getEntities();
        $this->template->users = $users;
    }

    /**
     * @param string $role
     * @param string $username
     */
    public function actionChangeRole(string $role, string $username)
    {
        $id = $this->userManager->getEntityByUnique($username)->user_id;
        $this->userManager->saveEntity(array(
            'user_id' => $id,
            'role' => $role
        ));

        $rolesEN = ['registered', 'editor', 'admin'];
        $rolesCS = ['uživatel', 'redaktor', 'administrátor'];
        $roleCS = $rolesCS[array_search($role, $rolesEN)];

        $this->flashMessage("Uživatel $username je nyní $roleCS", 'success');
        $this->redirect(':Admin:User:management');
    }
}
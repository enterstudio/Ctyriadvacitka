<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 6.12.16
 * Time: 16:23
 */

namespace App\CoreModule\Model;


use Nette\Security\Permission;

class AuthorizatorManager extends Permission
{
    public function __construct()
    {
        $this->addRole('guest');
        $this->addRole('registered', 'guest');
        $this->addRole('editor', 'registered');
        $this->addRole('admin', 'editor');

        $this->addResource('article');
        $this->addResource('user');
        $this->addResource('web');

        $this->allow('guest', ['article', 'user'], 'view');
        $this->allow('editor', 'article', 'add');
        $this->allow('admin', ['article', 'web'], 'edit');
        $this->allow('admin', 'user', 'remove');
    }
}

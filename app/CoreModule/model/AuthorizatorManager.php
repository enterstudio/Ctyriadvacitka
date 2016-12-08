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
    public function __construct(){
        $this->addRole('guest');
        $this->addRole('registered', 'guest');
        $this->addRole('administrator', 'registered');

        $this->addResource('article');
        $this->addResource('user');
        $this->addResource('comment');

        $this->allow('guest', ['article', 'user', 'comment'], 'view');
        $this->allow('registered', 'comment', 'add');
        $this->allow('administrator', ['article', 'user', 'comment'], ['edit', 'add']);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 5.12.16
 * Time: 21:49
 */

namespace App\CoreModule\Model;


use App\Model\BaseManager;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;

/**
 * Třída poskytuje metody pro ověření uživatelů
 * Class AuthenticatorManager
 * @package App\CoreModule\Model
 */
class AuthenticatorManager extends BaseManager implements IAuthenticator
{

    /**
     * Performs an authentication against e.g. database.
     * and returns IIdentity on success or throws AuthenticationException
     * @return IIdentity
     * @throws AuthenticationException
     */
    function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $row = $this->database->table('user')->where('username', $username)->fetch();

        if (!$row){
            throw new AuthenticationException('Uživatel nenalezen.');
        }

        if (Passwords::verify($password, $row->password)){
            throw new AuthenticationException('Špatné heslo!');
        }

        return new Identity($row->user_id, $row->role, ['username' => $row->username]);
    }
}
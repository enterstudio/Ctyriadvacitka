<?php
/**
 * Created by PhpStorm.
 * User: matyas
 * Date: 4.11.16
 * Time: 21:23
 */

namespace App\CoreModule\Model;


use App\Model\DatabaseHelper;
use Nette\Database\Context;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;

/**
 * User management
 * @package App\CoreModule\Model
 * @author matyas
 */
class UserManager extends EntityManager
{
    /**
     * konstanty pro manipulaci s modelem
     */
    const
        COLUMN_PASSWORD_HASH = 'password';

    /**
     * UserManager constructor.
     * @param Context $database
     * @param DatabaseHelper $databaseHelper
     */
    public function __construct(Context $database, DatabaseHelper $databaseHelper)
    {
        parent::__construct($database, $databaseHelper, 'user');
    }

    /**
     * Changes password of user
     * @param string $username of user to change password
     * @param string $currentPassword
     * @param string $newPassword
     * @throws AuthenticationException if current password is not correct
     */
    public function changePassword(string $username, string $currentPassword, string $newPassword)
    {
        $row = $this->database->table($this->getTable()->getName())->where('username', $username)->fetch();
        if (Passwords::verify($currentPassword, $row->password)) {
            $this->database->table($this->getTable()->getName())->where('username', $username)->update(array(
                self::COLUMN_PASSWORD_HASH => Passwords::hash($newPassword)
            ));
        } else {
            throw new AuthenticationException('Současné heslo neodpovídá zadanému současnému heslu!');
        }
    }

}
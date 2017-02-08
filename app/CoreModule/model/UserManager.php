<?php
/**
 * Created by PhpStorm.
 * User: matyas
 * Date: 4.11.16
 * Time: 21:23
 */

namespace App\CoreModule\Model;


use App\Model\BaseManager;
use Nette\Database\Table\IRow;
use Nette\Database\Table\Selection;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;

/**
 * User management
 * @package App\CoreModule\Model
 * @author matyas
 */
class UserManager extends BaseManager
{
    /**
     * konstanty pro manipulaci s modelem
     */
    const
        TABLE_NAME = 'user',
        COLUMN_ID = 'user_id',
        COLUMN_USERNAME = 'username',
        COLUMN_PASSWORD_HASH = 'password',
        COLUMN_ROLE = 'role',
        COLUMN_NAME = 'name',
        COLUMN_SURNAME = 'surname',
        COLUMN_NICKNAME = 'nickname',
        COLUMN_EMAIL = 'email';

    /**
     * Vrátí seznam uživatelů v databázi
     * @return Selection seznam uživatelů
     */
    public function getUsers()
    {
        return $this->database->table(self::TABLE_NAME)->order(self::COLUMN_ID . " " . "DESC");
    }

    /**
     * Vrátí uživatele podle ID nebo false
     * @param int $id id uživatele
     * @return bool|mixed|IRow
     */
    public function getUserByID(int $id)
    {
        return $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $id)->fetch();
    }

    /**
     * Vrátí uživatele podle jména nebo false
     * @param string $username jmeno uživatele
     * @return bool|mixed|IRow
     */
    public function getUserByUsername(string $username)
    {
        return $this->database->table(self::TABLE_NAME)->where(self::COLUMN_USERNAME, $username)->fetch();
    }

    /**
     * Zkontroluje, jestli existuje uživatel v databázi
     * @param string $username uživatelské jméno uživatele
     * @return bool existuje/neexistuje
     */
    public function userExists(string $username): bool
    {
        if ($this->getUserByUsername($username) != false)
            return true;
        else
            return false;
    }

    /**
     * Uloží uživatele do databáze. Pokud není nastaveno ID, vytvoří nového, jinak provede editaci.
     * @param array $user uživatel k uložení
     */
    public function saveUser(array $user)
    {
        if (empty($user[self::COLUMN_ID])) {
            $this->database->table(self::TABLE_NAME)->insert(
                array(
                    self::COLUMN_USERNAME => $user[0],
                    self::COLUMN_PASSWORD_HASH => $user[1],
                    self::COLUMN_ROLE => $user[2])
            );
        } else {
            foreach ($user as $key => $column) {
                if (empty($user[$key])) {
                    $user[$key] = '';
                }
            }
            $this->database->table(self::TABLE_NAME)->wherePrimary($user[self::COLUMN_ID])->update(
                array(
                    self::COLUMN_ID => $user[self::COLUMN_ID],
                    self::COLUMN_USERNAME => $user[self::COLUMN_USERNAME],
                    self::COLUMN_ROLE => $user[self::COLUMN_ROLE],
                    self::COLUMN_NAME => $user[self::COLUMN_NAME],
                    self::COLUMN_SURNAME => $user[self::COLUMN_SURNAME],
                    self::COLUMN_NICKNAME => $user[self::COLUMN_NICKNAME],
                    self::COLUMN_EMAIL => $user[self::COLUMN_EMAIL])
            );
        }
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
        $row = $this->database->table('user')->where('username', $username)->fetch();
        if (Passwords::verify($currentPassword, $row->password)) {
            $this->database->table('user')->where('username', $username)->update(array(
                self::COLUMN_PASSWORD_HASH => Passwords::hash($newPassword)
            ));
        } else {
            throw new AuthenticationException('Současné heslo neodpovídá zadanému současnému heslu!');
        }
    }

    /**
     * Smaže uživatele
     * @param int $id id uživatele
     */
    public function removeUser(string $username)
    {
        $this->database->table(self::TABLE_NAME)->where(self::COLUMN_USERNAME, $username)->delete();
    }

    /**
     * Vrátí databázi
     * @return \App\Model\Content|\Nette\Database\Context databáze
     */
    public function getDatabase()
    {
        return $this->database;
    }

}
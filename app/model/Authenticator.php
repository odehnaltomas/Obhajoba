<?php
/**
 * Created by PhpStorm.
 * User: Tom�
 * Date: 7. 11. 2015
 * Time: 21:49
 */

namespace App\Model;

use Nette;
use Nette\Security\IAuthenticator;
use Nette\Security\Passwords;


class Authenticator extends BaseManager implements IAuthenticator
{
    const
        TABLE_USER = 'user',
        USER_COLUMN_ID = 'id',
        USER_COLUMN_NAME = 'username',
        USER_COLUMN_PASSWORD = 'password',
        USER_COLUMN_SEX = 'sex',
        USER_COLUMN_ROLE = 'role_id',

        TABLE_USER_ROLE = 'user_role',
        ROLE_COLUMN_ID = 'id',
        ROLE_COLUMN_NAME = 'role';

    /** @var Nette\Database\Context */
    protected $database;


    /**
     * @param array $credentials
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;

        $row = $this->database->table(self::TABLE_USER)->where(self::USER_COLUMN_NAME, $username)->fetch();
        if (!$row) {
            throw new Nette\Security\AuthenticationException('Nesprávné uživatelské jméno!', self::IDENTITY_NOT_FOUND);

        } elseif (!Passwords::verify($password, $row[self::USER_COLUMN_PASSWORD])) {
            throw new Nette\Security\AuthenticationException('Nesprávné heslo!', self::INVALID_CREDENTIAL);

        } elseif (Passwords::needsRehash($row[self::USER_COLUMN_PASSWORD])) {
            $row->update(array(
                self::USER_COLUMN_PASSWORD => Passwords::hash($password),
            ));
        }

        $arr = $row->toArray();
        unset($arr[self::USER_COLUMN_PASSWORD]);
        return new Nette\Security\Identity($row[self::USER_COLUMN_ID], $row->TABLE_USER_ROLE[self::ROLE_COLUMN_NAME], $arr);
    }
}
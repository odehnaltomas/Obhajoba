<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
class UserManager extends BaseManager implements Nette\Security\IAuthenticator
{
	const
		TABLE_USER = 'uzivatel',
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
	 * Performs an authentication.
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
		return new Nette\Security\Identity($row[self::USER_COLUMN_ID], $row->role[self::ROLE_COLUMN_NAME], $arr);
	}


	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @return void
	 */
	public function add($username, $password)
	{
		try {
			$this->database->table(self::TABLE_NAME)->insert(array(
				self::COLUMN_NAME => $username,
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
			));
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}

}



class DuplicateNameException extends \Exception
{}

<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
class UserManager extends BaseManager
{
	/** @var Nette\Database\Context */
	protected $database;

	/**
	 * Adds new user.
	 * @param $username
	 * @param $password
	 * @throws DuplicateNameException
	 */
	public function add($values)
	{
		list($username, $password, $firstName, $lastName, $sex) = $values;

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

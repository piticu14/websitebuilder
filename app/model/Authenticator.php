<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 24.1.2018
 * Time: 16:31
 */

use Nette\Security;


class Authenticator implements Security\IAuthenticator
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $row = $this->database->table('users')
            ->where('username',$username)->fetch();
        if (!$row) {
            throw new Security\AuthenticationException("Uživatelské jméno '$username' neexistuje",self::IDENTITY_NOT_FOUND);
        }

        if(!password_verify($password, $row->password)){
            throw new Security\AuthenticationException("Vaše heslo je neplatné.",self::INVALID_CREDENTIAL);
        }

        return new Security\Identity($row->id,'', ['username' => $row->username]);
    }
}
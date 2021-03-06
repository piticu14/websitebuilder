<?php

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
        $row = $this->database->table('user')
            ->where('username',$username)->fetch();
        if (!$row) {
            throw new Security\AuthenticationException("Uživatelské jméno '$username' neexistuje",self::IDENTITY_NOT_FOUND);
        }


        if(!password_verify($password, $row->password)){
            throw new Security\AuthenticationException("Vaše heslo je neplatné.",self::INVALID_CREDENTIAL);
        }

        if(!$row->active) {
            throw new Security\AuthenticationException("Vaše heslo je neplatné.",self::NOT_APPROVED);
        }

        return new Security\Identity($row->id,'', ['username' => $row->username,'email' => $row->email]);
    }
}
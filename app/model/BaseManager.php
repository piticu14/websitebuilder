<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

abstract class BaseManager extends Nette\Object
{
    /**
     * @var Nette\Database\Context
     */
    private $database;

    /**
     * @var Nette\Security\User
     */
    private $user;


    /**
     * BaseManager constructor.
     * @param Nette\Database\Context $database
     * @param Nette\Security\User $user
     */

    public function __construct(Nette\Database\Context $database, Nette\Security\User $user)
    {
        $this->database = $database;
        $this->user = $user;
    }

    /**
     * @return Nette\Database\Context
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return Nette\Security\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function deleteFolder($path)
    {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file) {
                $this->deleteFolder(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        } else if (is_file($path) === true) {
            return unlink($path);
        }

        return false;
    }
}
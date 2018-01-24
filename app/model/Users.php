<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 24.1.2018
 * Time: 15:21
 */

namespace App\Model;

use Nette;
class Users extends BaseManager
{

    /**
     * @param array $data
     * @return bool|int|Nette\Database\Table\IRow
     */
    public function register($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->getDatabase()->table('users')->insert($data);
    }

}
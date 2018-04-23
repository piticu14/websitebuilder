<?php


namespace App\Model;

use Nette;


class UserEmail extends BaseManager
{
    private static $table = 'user_email';



    public function add($userId, $email, $isPrimary = 0)
    {
        return $this->getDatabase()->table(self::$table)->insert(
            [
                'user_id' => $userId,
                'email' => $email,
                'is_primary' => $isPrimary
            ]
        );
    }


    public function get($email)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('email', $email)
            ->fetch();
    }


    /**
     * Check if email already exists
     * @param string $email
     * @return int
     */

    public function check($email)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('email', $email)
            ->count();
    }

    public function activate($email)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('email', $email)
            ->update([
                'active' => 1
            ]);
    }

    public function getAll()
    {
        return $this->getDatabase()->table(self::$table)
            ->where('user_id', $this->getUser()->id)
            ->where('deleted_at',NULL)
            ->fetchAll();
    }

    public function setPrimary($email)
    {
        $this->getDatabase()->table(self::$table)
            ->where('is_primary', 1)
            ->update([
                'is_primary' => 0
            ]);
        return $this->getDatabase()->table(self::$table)
            ->where('email', $email)
            ->update([
                'is_primary' => 1
            ]);
    }

    public function delete($id)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('id',$id)
            ->update([
                'deleted_at' => date("Y-m-d H:i:s")
            ]);
    }


}
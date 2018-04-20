<?php

namespace App\Model;

use Nette;

class Users extends BaseManager
{

    private static $table = 'user';

    /**
     * @var UserEmail
     */
    private $userEmail;


    public function __construct(UserEmail $userEmail,Nette\Database\Context $database, Nette\Security\User $user)
    {
        parent::__construct($database,$user);
        $this->userEmail = $userEmail;
    }


    /**
     * @param array $data
     * @return bool|int|Nette\Database\Table\IRow
     */
    public function register($data)
    {
        return$this->getDatabase()->table(self::$table)->insert(
            [
                'username' => $data->username,
                'password' => password_hash($data->password, PASSWORD_DEFAULT),
                'email' => $data->email,
                'user_security_question_id' => $data->security_questions,
                'user_security_question_answer' => $data->security_question_answer,
            ]
        );
    }



    /**
     * Updates user's informations
     * @param array $data
     * @return int
     */
    public function update($data)
    {
        return $this->getDatabase()->table(self::$table)->update($data);
    }

    /**
     * Get user from database by specified column
     * @param string $column
     * @param string $value
     * @return bool|mixed|Nette\Database\Table\IRow|null
     */
    public function getBy($column,$value)
    {
        $user = $this->getDatabase()->table(self::$table)->where($column,$value);
        if($user->count()) {
            return $user->fetch();
        }
        return null;
    }
    /*---------------------------------Reset Password Methods----------------------------------- */

    /**
     * Check if username already exists
     * @param string $username
     * @return int
     */
    public function checkUsername($username)
    {
       return $this->getDatabase()->table(self::$table)
            ->where('username', $username)
            ->count();
    }


    /**
     * @return array
     */
    public function getSecurityQuestions()
    {
        return $this->getDatabase()->table('user_security_question')->fetchPairs('id','security_question');
    }

    /**
     * @param int $user_security_question_id
     * @return bool|mixed|Nette\Database\Table\IRow
     */
    public function getUserSecurityQuestion($user_security_question_id)
    {
        return $this->getDatabase()->table('user_security_question')->where('id',$user_security_question_id)->fetch();
    }





    /*---------------------------------User Activation Methods----------------------------------- */

    public function activate($email)
    {
        $this->userEmail->activate($email);
        $this->getDatabase()->table(self::$table)
            ->where('email',$email)
            ->update([
                'active' => 1
            ]);

    }

    public function isActive($email)
    {
            return $this->getBy('email', $email)->active;
    }


    /** TODO: Maybe delete active column from user and use active from email */
    public function setPrimaryEmail($email)
    {

        $userEmail = $this->userEmail->get($email);
        if($userEmail->active){
            $this->getDatabase()->table('user')
                ->where('id',$this->getUser()->id)
                ->update([
                    'email' => $email
                ]);
            $this->userEmail->setPrimary($email);
            return true;
        }

        return false;
    }

}
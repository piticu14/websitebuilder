<?php

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
        return$this->getDatabase()->table('user')->insert(
            [
                'username' => $data->username,
                'password' => password_hash($data->password, PASSWORD_DEFAULT),
                'email' => $data->email,
                'user_security_question_id' => $data->security_questions,
                'user_security_question_answer' => $data->security_question_answer,
            ]
        );
    }

    public function addEmail($userId, $email, $isPrimary = 0)
    {
        return $this->getDatabase()->table('user_email')->insert(
            [
                'user_id' => $userId,
                'email' => $email,
                'is_primary' => $isPrimary
            ]
        );
    }

    /**
     * Updates user's informations
     * @param array $data
     * @return int
     */
    public function updateUser($data)
    {
        return $this->getDatabase()->table('user')->update($data);
    }

    /**
     * Get user from database by specified column
     * @param string $column
     * @param string $value
     * @return bool|mixed|Nette\Database\Table\IRow|null
     */
    public function getUserBy($column,$value)
    {
        $user = $this->getDatabase()->table('user')->where($column,$value);
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
    public function usernameDuplicate($username)
    {
       return $this->getDatabase()->table('user')
            ->where('username', $username)
            ->count();
    }
    /**
     * Check if email already exists
     * @param string $email
     * @return int
     */

    public function emailDuplicate($email){
        return $this->getDatabase()->table('user_email')
            ->where('email',$email)
            ->count();
    }

    public function checkEmail($email)
    {
        return $this->getDatabase()->table('user_email')
            ->where('email',$email)
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

    public function activateUser($email)
    {
        $this->getDatabase()->table('user')->where('email',$email)
            ->update([
                'active' => 1
            ]);
        $this->getDatabase()->table('user_email')->where('email',$email)
            ->update([
                'active' => 1
            ]);

    }

    public function isUserActive($email)
    {
            return $this->getUserBy('email', $email)->active;
    }


    /*----------------------------------------User Emails------------------------- */
    public function getUserEmail($email){
        return $this->getDatabase()->table('user_email')->where('email',$email)->fetch();
    }

    public function activateUserEmail($email){
        return $this->getDatabase()->table('user_email')->where('email',$email)->update([
            'active' => 1
        ]);
    }

    public function getUserEmailsList()
    {
        return $this->getDatabase()->table('user_email')->where('user_id',$this->getUser()->id)->fetchAll();
    }

    public function setPrimaryEmail($email)
    {
        $this->getDatabase()->table('user')
            ->where('id',$this->getUser()->id)
            ->update([
                'email' => $email
            ]);
        $this->getDatabase()->table('user_email')
            ->where('is_primary',1)
            ->update([
                'is_primary' => 0
            ]);
        $this->getDatabase()->table('user_email')
            ->where('email',$email)
            ->update([
                'is_primary' => 1
            ]);

    }

}
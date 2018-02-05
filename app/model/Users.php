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

    /**
     * Updates user's informations
     * @param array $data
     * @return int
     */
    public function updateUser($data)
    {
        return $this->getDatabase()->table('users')->update($data);
    }

    /**
     * Get user from database by specified column
     * @param string $column
     * @param string $value
     * @return bool|mixed|Nette\Database\Table\IRow|null
     */
    public function getUserBy($column,$value)
    {
        $user = $this->getDatabase()->table('users')->where($column,$value);
        if($user->count()) {
            return $user->fetch();
        }
        return null;
    }
    /*---------------------------------Reset Password Methods----------------------------------- */

    /**
     * Check if the email used for reset password request exists
     * @param string $email
     * @return bool
     */
    public function checkEmail($email)
    {
        if($this->getDatabase()->table('users')->where('email',$email)->count()) {
            return true;
        }
        return false;
    }

    /**
     * Check if the RP token exists to be sure that user doesn't change the token in link
     * @param string $token
     * @return bool
     */

    public function checkRPToken($token){
        if($this->getDatabase()->table('reset_password_requests')->where('token',$token)->count()) {
            return true;
        }
        return false;
    }


    /**
     * Insert into database a new reset password request
     * @param string $email
     * @return bool|int|Nette\Database\Table\IRow
     */
    public function sendResetPasswordRequest($email)
    {
        $user = $this->getUserBy('email',$email);
        $data = array(
            'user_id' => $user->id,
            'token' => bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)),
            'sent_at' => date("Y-m-d H:i:s")
        );
        return $this->getDatabase()->table('reset_password_requests')->insert($data);
    }

    /**
     * @return array
     */
    public function getSecurityQuestions()
    {
        return $this->getDatabase()->table('user_security_questions')->fetchPairs('id','security_question');
    }

    /**
     * @param int $user_security_question_id
     * @return bool|mixed|Nette\Database\Table\IRow
     */
    public function getUserSecurityQuestion($user_security_question_id)
    {
        return $this->getDatabase()->table('user_security_questions')->where('id',$user_security_question_id)->fetch();
    }

    /**
     * RP means Reset Password
     * @param string $token
     * @return bool|mixed|Nette\Database\Table\IRow|null
     */
    public function getRPRequestRequest($token){
        $request = $this->getDatabase()->table('reset_password_requests')->where('token',$token);

        if($request) {
            return $request->fetch();
        }
        return null;
    }

    /**
     * All old user password resquests will be deleted
     * @param int $userId
     */
    public function deleteOldRPRequests($userId)
    {
        $this->getDatabase()->table('reset_password_requests')->where('user_id',$userId)->delete();
    }

    /*---------------------------------User Activation Methods----------------------------------- */
    public function checkActivationToken($token) {
        return $this->getUserBy('activation_token',$token);
    }

    public function activateUser($token)
    {
        $this->getDatabase()->table('users')->where('activation_token',$token)
            ->update([
                'activated' => 1
            ]);
    }

    public function isUserActivated($token = null) {
        if($token) {
            return $this->getUserBy('activation_token', $token)->activated;
        } else {
            return $this->getUserBy('id', $this->getUser()->id)->activated;
        }
    }

}
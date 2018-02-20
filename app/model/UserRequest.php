<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 10.2.2018
 * Time: 1:19
 */

namespace App\Model;

use Nette;


class UserRequest extends BaseManager
{
    /**
     * Check if the request token exists to be sure that user doesn't change the token in link
     * @param string $token
     * @return bool
     */

    public function checkToken($token){
        if($this->getDatabase()->table('user_requests')->where('token',$token)->count()) {
            return true;
        }
        return false;
    }

    /**
     * Insert into database a new user request
     * @param id $userEmailId
     * @param string $type
     * @return bool|int|Nette\Database\Table\IRow
     */
    public function addRequest($userEmailId, $type)
    {
        $data = array(
            'user_email_id' => $userEmailId,
            'type' => $type,
            'token' => bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)),
            'sent_at' => date("Y-m-d H:i:s")
        );
        return $this->getDatabase()->table('user_requests')->insert($data);
    }

    /**
     * @param string $token
     * @param string $type
     * @return bool|mixed|Nette\Database\Table\IRow|null
     */
    public function getUserRequest($token, $type){
        $request = $this->getDatabase()->table('user_requests')
            ->where('token',$token)
            ->where('type',$type);

        if($request) {
            return $request->fetch();
        }
        return null;
    }

    /**
     * Delete all old requests
     * @param int $emailId
     * @param string $type
     */
    public function deleteOldUserRequests($emailId, $type)
    {
        $this->getDatabase()->table('user_requests')->where('user_email_id',$emailId)
            ->where('type',$type)
            ->delete();
    }

    public function getUserEmail($token){
        $userRequest = $this->getDatabase()->table('user_requests')
            ->where('token',$token)->fetch();
        return $this->getDatabase()->table('user_emails')
            ->where(':user_requests.user_email_id',$userRequest->user_email_id)->fetch();
    }

}
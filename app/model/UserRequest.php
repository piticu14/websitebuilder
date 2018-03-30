<?php

namespace App\Model;

use Nette;

//TODO: If user used the request set it in DB
//TODO: Settings -> EmailVerification - If user ask for resending EmailVerification request, delete the old one

class UserRequest extends BaseManager
{
    /**
     * Check if the request token exists to be sure that user doesn't change the token in link
     * @param string $token
     * @return bool
     */

    public function checkToken($token){
        if($this->getDatabase()->table('user_request')->where('token',$token)->count()) {
            return true;
        }
        return false;
    }

    /**
     * Insert into database a new user request
     * @param int $userEmailId
     * @param string $type
     * @return bool|int|Nette\Database\Table\IRow
     */
    public function add($userEmailId, $type)
    {
        $data = array(
            'user_email_id' => $userEmailId,
            'type' => $type,
            'token' => bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)),
            'sent_at' => date("Y-m-d H:i:s")
        );
        return $this->getDatabase()->table('user_request')->insert($data);
    }

    /**
     * @param string $token
     * @param string $type
     * @return bool|mixed|Nette\Database\Table\IRow|null
     */
    public function get($token, $type){
        $request = $this->getDatabase()->table('user_request')
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
    public function deleteOld($emailId, $type)
    {
        $this->getDatabase()->table('user_request')->where('user_email_id',$emailId)
            ->where('type',$type)
            ->delete();
    }

    public function getEmail($token){
        $userRequest = $this->getDatabase()->table('user_request')
            ->where('token',$token)->fetch();
        return $this->getDatabase()->table('user_email')
            ->where(':user_request.user_email_id',$userRequest->user_email_id)->fetch();
    }

}
<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 24.1.2018
 * Time: 15:21
 */

namespace App\Model;

use Nette;
use Latte;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

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

    public function updateUser($data)
    {
        $this->getDatabase()->table('users')->update($data);
    }
    /**
     * @param string $email
     * @return bool
     */
    public function checkEmail($email)
    {
        if($this->getDatabase()->table('users')->where('email',$email)) {
            return true;
        }
        return false;
    }

    public function sendResetPasswordEmail($email)
    {
        $user = $this->getDatabase()->table('users')->where('email',$email)->fetch();
        $latte = new Latte\Engine;
        $params = [
            'email' => $user->email,
            'token' => $user->token
        ];

        $mail = new Message;
        $mail->setFrom('FastWeb <resetPassword@fastweb.cz>')
            ->addTo($user->email)
            ->setHtmlBody($latte->renderToString('resetPasswordEmail.latte', $params));
        $mailer = new SendmailMailer;
        $mailer->send($mail);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 27.1.2018
 * Time: 11:53
 */

namespace App\Model;

use Nette\Mail\Message;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Mail\IMailer;


class EmailNotification
{
    /**
     * @var IMailer
     */
    private $mailer;

    /**
     * @var ILatteFactory
     */
    private $latteFactory;

    /**
     * @var string
     */
    private $emailDir;

    /**
     * EmailNotification constructor.
     * @param string $emailDir
     * @param IMailer $mailer
     * @param ILatteFactory $latteFactory
     */

    public function __construct($emailDir, IMailer $mailer, ILatteFactory $latteFactory)
    {
        $this->emailDir = $emailDir;
        $this->mailer = $mailer;
        $this->latteFactory = $latteFactory;
    }

    public function send($param)
    {
        $message = new Message();

        $message->setFrom($param['from'])
            ->addTo($param['to'])
            ->setSubject($param['subject']);

        $latte = $this->latteFactory->create();

        $message->setHtmlBody($latte->renderToString($this->emailDir . $param['email_template'] . '.latte', $param['body']));

        $this->mailer->send($message);
    }
}
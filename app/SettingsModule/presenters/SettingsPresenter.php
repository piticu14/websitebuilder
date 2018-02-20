<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 7.2.2018
 * Time: 21:02
 */

namespace App\Presenters;
use Nette;
use Nette\Application\UI\Form;

use App\Model\Users;
use App\Model\UserRequest;
use App\Model\EmailNotification;


class SettingsPresenter extends DashboardBasePresenter
{
    /** @var Users */
    private $users;

    /** @var EmailNotification */
    private $emailNotification;

    /** @var UserRequests */
    private $userRequest;

    /**
     * AccountPresenter constructor.
     * @param Users $users
     * @param EmailNotification $emailNotification
     * @param UserRequest $userRequest
     */

    public function __construct(Users $users, EmailNotification $emailNotification, UserRequest $userRequest)
    {
        $this->users = $users;
        $this->emailNotification = $emailNotification;
        $this->userRequest = $userRequest;
    }

    protected function createComponentResetPasswordForm()
    {
        $form = (new \ResetPasswordFormFactory($this->users, $this))->create();
        $form->addPassword('old_password')
            ->setRequired('Zadejte své staré heslo');
        $form->onSuccess[] = function (Form $form) {
            $this->flashMessage('Váš účet byl úspěšně založen. Ověřte svůj účet pomoci emailu, který Vám právě přišel.', 'success');
            $this->redirect('Account:signin');
        };
        return $form;

    }

    protected function createComponentAddEmailForm()
    {
        $form = (new \EmailFormFactory())->create('Zadejte nový email','Přidat');
        $form->onSuccess[] = [$this, 'addEmailFormSucceeded'];
        return $form;
    }

    public function addEmailFormSucceeded($form, $values)
    {
        $userEmail = $this->users->addEmail($this->getUser()->id,$values->email);
        $this->sendEmailActivation($userEmail);
        $this->flashMessage('Právě jsme Vám poslali email s ověřením emailové adresy.', 'success');
        $this->redirect('this');

    }

    public function actionEmailActivation($token){
        if($this->userRequest->checkToken($token)) {
            $userEmail = $this->userRequest->getUserEmail($token);
            if($userEmail->active) {
                $this->flashMessage('Vaše emailová adresa je již ověřená.');
            }else {
                $this->users->activateUserEmail($userEmail->email);
                $this->flashMessage('Vaše emailová adresa byla úspěšně ověřená.');
            }
            $this->redirect('Settings:default');
        }
    }

    public function handleActivateEmail($email)
    {
        if($this->isAjax()) {
            $userEmail = $this->users->getUserEmail($email);
            $this->sendEmailActivation($userEmail);
            $this->payload->boxId = 2;
            $this->flashMessage('Právě jsme Vám poslali email s ověřením emailové adresy.', 'success');
            $this->redrawControl('flashes');
        }
    }

    private function sendEmailActivation($userEmail)
    {
        $param = array(
            'from' => 'FastWeb <support@fastweb.cz>',
            'to' => $userEmail->email,
            'subject' => 'Potvrzeni emailové adresy',
            'email_template' => 'emailActivation',
            'body' => array(
                'request' => 'email',
                'token' => $this->userRequest->addRequest($userEmail->id,'emailVerification')->token,
                'link' => $this->getHttpRequest()->getUrl()->getBaseUrl() . 'activate/email'
            )
        );
        $this->emailNotification->send($param);
    }

    public function handleSetPrimary($email)
    {
        if($this->isAjax()) {
            $this->users->setPrimaryEmail($email);
            $this->template->userEmails = $this->users->getUserEmailsList();
        }
        $this->redrawControl('emailSettings');

    }

    public function renderDefault()
    {
        if (!isset($this->template->userEmails)) {
            $this->template->userEmails = $this->users->getUserEmailsList();
        }
    }
}
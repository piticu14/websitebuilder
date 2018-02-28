<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

use App\Model\Users;
use App\Model\UserRequest;
use App\Model\EmailNotification;
use App\Model\ProjectManager;


class SettingsPresenter extends AdminBasePresenter
{
    /** @var Users */
    private $users;

    /** @var EmailNotification */
    private $emailNotification;

    /** @var UserRequest */
    private $userRequest;

    /** @var ProjectManager */
    private $projectManager;


    /**
     * AccountPresenter constructor.
     * @param Users $users
     * @param EmailNotification $emailNotification
     * @param UserRequest $userRequest
     * @param ProjectManager $projectManager
     */

    public function __construct(Users $users, EmailNotification $emailNotification, UserRequest $userRequest, ProjectManager $projectManager)
    {
        $this->users = $users;
        $this->emailNotification = $emailNotification;
        $this->userRequest = $userRequest;
        $this->projectManager = $projectManager;
    }

    public function addEmailFormSucceeded($form, $values)
    {
        $userEmail = $this->users->addEmail($this->getUser()->id,$values->email);
        $this->sendEmailActivation($userEmail);
        $this->flashMessage('Právě jsme Vám poslali email s ověřením emailové adresy.', 'success');
        $this->redirect('this');

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

    public function handleSetPrimary($email)
    {
        if($this->isAjax()) {
            $this->users->setPrimaryEmail($email);
            $this->template->userEmails = $this->users->getUserEmailsList();
        }
        $this->redrawControl('emailSettings');

    }

    public function renderDefault($id)
    {
        if (!isset($this->template->userEmails)) {
            $this->template->userEmails = $this->users->getUserEmailsList();
        }
    }

    protected function createComponentResetPasswordForm()
    {
        $form = (new \ResetPasswordFormFactory($this->users, $this))->create();
        $form->addPassword('old_password','Staré heslo')
            ->setRequired();
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

    protected function createComponentProjectSettingsForm()
    {
        $form = (new \ProjectSettingsForm($this->projectManager))->create($this->getParameter('id'));
        $form->onSuccess[] = function (Form $form){
            $this->flashMessage('Aktualizace uspesna');
            $this->redirect('Project:all');
        };
        return $form;
    }
}
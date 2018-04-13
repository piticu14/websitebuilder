<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;

use App\Model\Users;
use App\Model\UserRequest;
use App\Model\UserEmail;
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

    /** @var UserEmail */
    private $userEmail;


    /**
     * AccountPresenter constructor.
     * @param Users $users
     * @param EmailNotification $emailNotification
     * @param UserRequest $userRequest
     * @param ProjectManager $projectManager
     * @param UserEmail $userEmail
     */

    public function __construct(Users $users,
                                EmailNotification $emailNotification,
                                UserRequest $userRequest,
                                ProjectManager $projectManager,
                                UserEmail $userEmail)
    {
        $this->users = $users;
        $this->emailNotification = $emailNotification;
        $this->userRequest = $userRequest;
        $this->projectManager = $projectManager;
        $this->userEmail = $userEmail;
    }

    public function addEmailFormSucceeded($form, $values)
    {

        if ($this->userEmail->check($values['email'])) {
            $this->flashMessage('Emailová adresa již v databázi existuje.', 'danger');
            $this->redrawControl('flashes');
        } else {
            $userEmail = $this->userEmail->add($this->getUser()->id, $values->email);
            $this->sendEmailActivation($userEmail);
            $this->flashMessage('Právě jsme Vám poslali email s ověřením emailové adresy.', 'success');
            $this->redirect('this');
        }

    }

    private function sendEmailActivation($userEmail)
    {

        $this->userRequest->deleteOld($userEmail->id, 'emailVerification');
        $param = array(
            'from' => 'FastWeb <support@fastweb.cz>',
            'to' => $userEmail->email,
            'subject' => 'Potvrzeni emailové adresy',
            'email_template' => 'emailActivation',
            'body' => array(
                'request' => 'email',
                'token' => $this->userRequest->add($userEmail->id, 'emailVerification')->token,
                'link' => $this->getHttpRequest()->getUrl()->getBaseUrl() . 'activate/email'
            )
        );
        //$this->emailNotification->send($param);
    }

    public function actionEmailActivation($token)
    {

        if($token) {
            if($this->userRequest->checkToken($token) > 0){
                $RPrequest = $this->userRequest->get($token,'emailVerification');
                $now = DateTime::from(date("Y-m-d H:i:s"));
                $RPRequestTime = DateTime::from($RPrequest->sent_at);
                $RPRequestExpiredTime = $RPRequestTime->modifyClone('+10 minutes');
                if($now > $RPRequestExpiredTime) {
                    $this->setView('../../../../app/presenters/templates/Error/410');
                } else {
                    $userEmail = $this->userRequest->getEmail($token);
                    if ($userEmail->active) {
                        $this->flashMessage('Vaše emailová adresa je již ověřená.');
                    } else {
                        $this->userEmail->activate($userEmail->email);
                        $this->flashMessage('Vaše emailová adresa byla úspěšně ověřená.');
                    }
                    $this->redirect('Settings:default');
                    $this->userRequest->used($token);

                }
            } else {
                $this->setView('../../../../app/presenters/templates/Error/410');
            }
        }
        else {
            $this->setView('../../../../app/presenters/templates/Error/410');
        }
    }

    public function handleActivateEmail($email)
    {
        if ($this->isAjax()) {
            $userEmail = $this->userEmail->get($email);
            $this->sendEmailActivation($userEmail);
            $this->payload->boxId = 2;
            $this->flashMessage('Právě jsme Vám poslali email s ověřením emailové adresy.', 'success');
            $this->redrawControl('flashes');
        }
    }

    public function handleSetPrimary($email)
    {
        if ($this->isAjax()) {
            if ($this->users->setPrimaryEmail($email)) {
                $this->template->userEmails = $this->userEmail->getAll();
            } else {
                $this->flashMessage('Váš email není aktivován. Nelze nastavit jako primární', 'danger');
                $this->redrawControl('flashes');
            }

        }
        $this->redrawControl('emailSettings');

    }

    public function renderDefault($id)
    {
        if (!isset($this->template->userEmails)) {
            $this->template->userEmails = $this->userEmail->getAll();
        }
    }

    protected function createComponentResetPasswordForm()
    {
        $form = (new \ResetPasswordFormFactory($this->users, $this))->create();
        $form->addPassword('old_password', 'Staré heslo')
            ->setRequired();
        $form->onSuccess[] = function (Form $form) {
            $this->flashMessage('Váš účet byl úspěšně založen. Ověřte svůj účet pomoci emailu, který Vám právě přišel.', 'success');
            $this->redirect('Account:signin');
        };
        return $form;

    }

    protected function createComponentAddEmailForm()
    {
        $form = (new \EmailFormFactory())->create('Zadejte nový email', 'Přidat');
        $form->onSuccess[] = [$this, 'addEmailFormSucceeded'];
        return $form;
    }

    protected function createComponentProjectSettingsForm()
    {
        $form = (new \ProjectSettingsForm($this->projectManager))->create($this->getParameter('id'));
        $form->onSuccess[] = function (Form $form) {
            $this->flashMessage('Aktualizace uspesna');
            $this->redirect('Project:all');
        };
        return $form;
    }
}
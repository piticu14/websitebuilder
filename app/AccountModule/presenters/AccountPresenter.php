<?php

//TODO: Edit email template graphic

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use App\Model\Users;
use App\Model\UserRequest;
use App\Model\EmailNotification;
use Latte;


class AccountPresenter extends FrontBasePresenter
{
    /** @var Users */
    private $users;

    /** @var EmailNotification */
    private $emailNotification;

    /** @var UserRequest */
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

    protected function createComponentSignupForm()
    {
        $form = (new \SignupFormFactory($this->users, $this->emailNotification, $this->userRequest, $this->getHttpRequest()->getUrl()->getBaseUrl()))->create();

        $form->onValidate[] = function ($form) {
            $form['signup']->setDisabled();
            if($this->users->checkUsername($form['username']->value)){
                $form['username']->addError('Uživatelské jméno již existuje');
            }
            if($this->users->checkEmail($form['email']->value)) {
                $form['email']->addError('Emailová adresa již existuje');
            }
        };
        $form->onSuccess[] = function (Form $form) {
            $this->flashMessage('Váš účet byl úspěšně založen. Ověřte svůj účet pomoci emailu, který jsme Vám právě odeslali.', 'success');
            $this->redirect('Account:signin');
        };
        return $form;

    }

    protected function createComponentSigninForm()
    {

            return (new \SigninFormFactory($this->users,$this))->create();
    }


    protected function createComponentEmailVerificationForm()
    {
        return (new \EmailFormFactory)->create('Zadejte email Vašého účtu', 'Obnovit');
    }


    protected function createComponentResetPasswordForm()
    {
        return (new \ResetPasswordFormFactory($this->users,$this))->create();
    }

    protected function createComponentSecurityQuestionForm()
    {
        return (new \SecurityQuestionFormFactory())->create();
    }

    protected function createComponentChangePasswordForm()
    {
        $form = (new \ResetPasswordFormFactory($this->users,$this))->create();
        $form->addPassword('oldPassword')
            ->setRequired('Zadejte staré heslo');
    }
    public function handleSendPasswordLink($user_question_answer, $emailAddress, $user_security_question)
    {

        if($this->isAjax()){
            $user = $this->users->getBy('email',$emailAddress);
            if($user->user_security_question_answer === $user_question_answer) {
                $userEmail = $this->users->getEmail($user->email);
                $this->userRequest->deleteOld($userEmail->id,'resetPassword');
                $param = array(
                    'from' => 'FastWeb <support@fastweb.cz>',
                    'to' => $emailAddress,
                    'subject' => 'Obnoveni hesla',
                    'email_template' => 'resetPasswordEmail',
                    'body' => array(
                        'request' => 'account',
                        'token' => $this->userRequest->add($userEmail->id,'resetPassword')->token,
                        'link' => $this->getHttpRequest()->getUrl()->getBaseUrl()
                    )
                );
                $this->emailNotification->send($param);
                $this->flashMessage('Resetování bylo úspěšné. Právě jsme Vám poslali e-mail s odkazem na obnovení hesla.', 'success');
                $this->redirect('Account:Signin');
            } else {
                $this->flashMessage('Vaše odpověď na bezpečností otázku je nesprávná','danger');
                $this->template->user_found= true;
                $this->template->email = $emailAddress;
                $this->template->security_question = $user_security_question;
                $this->redrawControl('resetPassword');

            }
        }

    }


    public function handleCheckEmail($emailAddress)
    {
        //using $emailAddress instead of $email because $email is uset in actionReset and show email address in link
        if ($this->isAjax())
        {
            $user = $this->users->getBy('email',$emailAddress);
            if($user){
                $this->template->security_question = $this->users->getUserSecurityQuestion($user->user_security_question_id)->security_question;
                $this->template->user_found= true;
                $this->template->email = $emailAddress;

            } else {
                $this->template->user_found= false;
                $this->flashMessage('Emailova adresa nebyla nalzena.','warning');
            }
        }
        $this->redrawControl('resetPassword');

        /*
        $this->sendJson((object)[
            'email' => $email,
        ]);
        */
    }

    public function actionReset($token){
        $this->template->user_found = false;
        if($token) {
            if($this->userRequest->checkToken($token) > 0){
                $RPrequest = $this->userRequest->get($token,'resetPassword');
                $now = DateTime::from(date("Y-m-d H:i:s"));
                $RPRequestTime = DateTime::from($RPrequest->sent_at);
                $RPRequestExpiredTime = $RPRequestTime->modifyClone('+10 minutes');
                if($now > $RPRequestExpiredTime) {
                    $this->setView('../../../../app/presenters/templates/Error/410');
                } else {
                    $this->userRequest->used($token);
                }
            } else {
                $this->setView('../../../../app/presenters/templates/Error/410');
            }
        }
    }

    public function actionActivation($token)
    {
        if($this->userRequest->checkToken($token)) {
            $userEmail = $this->userRequest->getEmail($token);

            if($this->users->isActive($userEmail->email)) {
                $this->flashMessage('Váš účet je již aktivován.','info');
                $this->redirect('Account:signin');
            }
            $this->users->activate($userEmail->email);
            $this->userRequest->used($token);
            $this->flashMessage('Váš účet byl aktivován. Můžete se přihlásit');
            $this->redirect('Account:signin');
        } else {
            $this->setView('../../../../app/presenters/templates/Error/410');
        }

    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->redirect('Account:signin');
    }
}
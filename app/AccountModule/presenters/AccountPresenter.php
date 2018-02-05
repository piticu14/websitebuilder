<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 24.1.2018
 * Time: 13:41
 */

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use App\Model\Users;
use App\Model\EmailNotification;
use Latte;


class AccountPresenter extends BasePresenter
{
    /** @var Users */
    private $users;

    /** @var EmailNotification */
    private $emailNotification;


    public function __construct(Users $users, EmailNotification $emailNotification)
    {
        $this->users = $users;
        $this->emailNotification = $emailNotification;


    }
    /*
     * -----------------------------------------Signup Form----------------------------------------------------------
     */

    protected function createComponentSignupForm()
    {

        $securityQuestions = $this->users->getSecurityQuestions();
        $form = new Form;
        $form->addText('username')
            ->setRequired('Prosim vyplňte uživatelské jméno')
            ->addRule(Form::MIN_LENGTH, 'Uživatelské jméno mít alespoň %d znaky', 4)
            ->addRule(Form::MAX_LENGTH, 'Uživatelské jméno mít nejvíce %d znaky', 100);
        $form->addPassword('password')
            ->setRequired('password')
            ->setRequired('Prosím vyplňte heslo')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 5);
        $form->addPassword('password2')
            ->setRequired('Prosim vyplňte znovu heslo')
            ->addConditionOn($form['password'], Form::VALID)
            ->addRule(Form::EQUAL, 'Hesla se neshodují.', $form['password']);
        $form->addEmail('email')
            ->addRule(Form::EMAIL, 'Neplatná emailová adresa')
            ->setRequired('Prosím vyplňte emailovou adresu')
            ->addRule(Form::MAX_LENGTH, 'Emailová adresa může mít nejvíce %d znaky', 254);
        $form->addSelect('security_questions', 'Bezpečnostní otázka', $securityQuestions);
        $form->addText('security_question_answer')
            ->setRequired('Prosim vyplňte odpověď na bezpečnostní otázku')
            ->addRule(Form::MIN_LENGTH, 'Uživatelské jméno mít alespoň %d znaky', 1)
            ->addRule(Form::MAX_LENGTH, 'Uživatelské jméno mít nejvíce %d znaky', 100);
        $form->addSubmit('signup', 'Registrovat');

        $form->onSuccess[] = [$this, 'signupFormSucceeded'];
        return $form;
    }

    /**
     * @param $form Nette\Application\UI\Form
     * @param $values Nette\utils\ArrayHash
     */

    public function signupFormSucceeded($form, $values)
    {

        $data = [
            'username' => $values->username,
            'password' => $values->password,
            'email' => $values->email,
            'user_security_question_id' => $values->security_questions,
            'user_security_question_answer' => $values->security_question_answer,
            'activation_token' => bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM))
        ];

        $new_user = $this->users->register($data);
        if ($new_user) {
            $param = array(
                'from' => 'FastWeb <support@fastweb.cz>',
                'to' => $values->email,
                'subject' => 'Aktivace účtu',
                'email_template' => 'userActivation',
                'body' => array(
                    'token' => $data['activation_token']
                )
            );
            $this->emailNotification->send($param);
            $this->flashMessage('Váš účet byl úspěšně založen. Ověřte svůj účet pomoci emailu, který Vám právě přišel.', 'success');
            $this->redirect('Account:signin');
        } else {
            //Use Ajax to check if username exists
        }
    }

    /*
     * -----------------------------------------Signin Form----------------------------------------------------------
     */
    protected function createComponentSigninForm()
    {
        $form = new Form;
        $form->addtext('username')
            ->setRequired('Prosím vyplňte uživatelské jméno');
        $form->addPassword('password')
            ->setRequired('Prosím vyplňte heslo');
        $form->addSubmit('signin', 'Přihlásit');
        $form->onSuccess[] = [$this, 'signinFormSucceeded'];
        return $form;
    }

    public function signinFormSucceeded($form, $values)
    {
        try {
            $this->getUser()->login($values->username, $values->password);
            if(!$this->users->isUserActivated()) {
                $this->flashMessage('Váš účet není aktivován. Aktivujte ho pomoci emailu, který jste obdržel');
                $this->redirect('Account:signin');
            }
            $this->redirect('Homepage:');
        } catch (Nette\Security\AuthenticationException $e) {
            $this->flashMessage('Přihlášení bylo neúspěšné. Zkontrolujte své přihlašovací údaje.','danger');
        }
    }

    /*
 * -----------------------------------------Reset password Forms-------------------------------------------------------
 */
    protected function createComponentEmailVerificationForm()
    {
        $form = new Form;
        $form->addEmail('email')
            ->setRequired('Zadejte prosím Email Vašého účtu');
        $form->addSubmit('reset', 'Obnovit');
        return $form;

    }


    protected function createComponentResetPasswordForm()
    {
        $form = new Form;
        $form->addPassword('password')
            ->setRequired('password')
            ->setRequired('Prosím vyplňte heslo')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 5);
        $form->addPassword('password2')
            ->setRequired('Prosim vyplňte znovu heslo')
            ->addConditionOn($form['password'], Form::VALID)
            ->addRule(Form::EQUAL, 'Hesla se neshodují.', $form['password']);
        $form->addSubmit('reset', 'Nastavit heslo');
        $form->addProtection('Vypršel ochranný časový limit, odešlete prosím formulář ještě jednou');
        $form->onSuccess[] = [$this, 'resetPasswordFormSucceded'];
        return $form;
    }

    public function resetPasswordFormSucceded($form, $values)
    {
        $data = array();
        $data['password'] = password_hash($values->password, PASSWORD_DEFAULT);

        if ($this->users->updateUser($data)) {
            $this->flashMessage('Vaše heslo bylo úspěšně změneno. Můžete se přihlásit novým heslem.', 'success');
        } else {
            $this->flashMessage('Nepodařilo se nastavit nové heslo', 'warning');
        }
        $this->redirect('Account:signin');
    }

    protected function createComponentSecurityQuestionForm()
    {
        $form = new Form;
        $form->addText('security_question');
        $form->addText('security_question_answer')
            ->setRequired('Odpovězte na bezpečnostní otázku');
        $form->addSubmit('reset', 'Obnovit');
        return $form;
    }

    /*
    * -----------------------------------------Reset password AJAX requests-------------------------------------------
    */
    public function handleSendPasswordLink($user_question_answer, $emailAddress, $user_security_question)
    {

        if($this->isAjax()){
            $user = $this->users->getUserBy('email',$emailAddress);
            if($user->user_security_question_answer === $user_question_answer) {
                $this->users->deleteOldRPRequests($user->id);
                $param = array(
                    'from' => 'FastWeb <resetPassword@fastweb.cz>',
                    'to' => $emailAddress,
                    'subject' => 'Obnoveni hesla',
                    'email_template' => 'resetPasswordEmail',
                    'body' => array(
                        'email' => $emailAddress,
                        'token' => $this->users->sendResetPasswordRequest($emailAddress)->token
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

    public function actionReset($email, $token){
        $this->template->user_found = false;
        if($email && $token) {
            if($this->users->checkEmail($email) && $this->users->checkRPToken($token)){
                $RPrequest = $this->users->getRPRequestRequest($token);
                $now = DateTime::from(date("Y-m-d H:i:s"));
                $RPRequestTime = DateTime::from($RPrequest->sent_at);
                $RPRequestExpiredTime = $RPRequestTime->modifyClone('+10 minutes');
                if($now > $RPRequestExpiredTime) {
                    $this->setView('../../../../app/presenters/templates/Error/410');
                }
            } else {
                $this->setView('../../../../app/presenters/templates/Error/410');
            }

        }
    }

    public function handleCheckEmail($emailAddress)
    {
        //using $emailAddress instead of $email because $email is uset in actioReset and show email address in link
        if ($this->isAjax())
        {
            $user = $this->users->getUserBy('email',$emailAddress);
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

    public function actionActivation($token)
    {
        if($this->users->checkActivationToken($token)) {
            if($this->users->isUserActivated($token)) {
                $this->flashMessage('Váš účet je již aktivován.','info');
                $this->redirect('Account:signin');
            }
            $this->users->activateUser($token);
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
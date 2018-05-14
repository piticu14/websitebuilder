<?php

use Nette\Application\UI\Form;

use App\Model\Users;
use App\Model\EmailNotification;
use App\Model\UserRequest;
use App\Model\UserEmail;

class SignupFormFactory
{
    /** @var Users */
    private $users;

    /** @var EmailNotification */
    private $emailNotification;

    /** @var UserRequest */
    private $userRequest;

    /** @var UserEmail */
     private $userEmail;

    /** @var string */
    private $baseUrl;

    /**
     * SignupFormFactory constructor.
     * @param Users $users
     * @param EmailNotification $emailNotification
     * @param UserRequest $userRequest
     * @param UserEmail $userEmail
     * @param string $baseUrl
     */

    public function __construct(Users $users, EmailNotification $emailNotification, UserRequest $userRequest, \App\Model\UserEmail $userEmail, $baseUrl)
    {
        $this->users = $users;
        $this->emailNotification = $emailNotification;
        $this->userRequest = $userRequest;
        $this->userEmail = $userEmail;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return Form
     */
    public function create()
    {
        $securityQuestions = $this->users->getSecurityQuestions();

        $form = new Form;
        $form->addText('username','Uživatelské jméno')
            ->setRequired('Prosim vyplňte uživatelské jméno')
            ->addRule(Form::MIN_LENGTH, 'Uživatelské jméno mít alespoň %d znaky', 4)
            ->addRule(Form::MAX_LENGTH, 'Uživatelské jméno mít nejvíce %d znaky', 100);
        $form->addPassword('password', 'Heslo')
            ->setRequired('password')
            ->setRequired('Prosím vyplňte heslo')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 5);
        $form->addPassword('password2', 'Kontrolní heslo')
            ->setRequired('Prosim vyplňte znovu heslo')
            ->addConditionOn($form['password'], Form::VALID)
            ->addRule(Form::EQUAL, 'Hesla se neshodují.', $form['password']);
        $form->addEmail('email', 'Emailová adresa')
            ->addRule(Form::EMAIL, 'Neplatná emailová adresa')
            ->setRequired('Prosím vyplňte emailovou adresu')
            ->addRule(Form::MAX_LENGTH, 'Emailová adresa může mít nejvíce %d znaky', 254);
        $form->addSelect('security_questions', 'Bezpečnostní otázka', $securityQuestions);
        $form->addText('security_question_answer','Bezpečnostní odpověď')
            ->setRequired('Prosim vyplňte odpověď na bezpečnostní otázku')
            ->addRule(Form::MIN_LENGTH, 'Uživatelské jméno mít alespoň %d znaky', 1)
            ->addRule(Form::MAX_LENGTH, 'Uživatelské jméno mít nejvíce %d znaky', 100);
        $form->addSubmit('signup', 'Registrovat');

        $form->onSuccess[] = [$this, 'signupFormSucceeded'];
        return $form;
    }

    public function signupFormSucceeded($form, $values)
    {
                if(($new_user = $this->users->register($values)) !== false) {
                    $userEmail = $this->userEmail->add($new_user->id,$values->email,1);
                    $param = array(
                        'from' => 'QuickWeb <support@quickweb.fun>',
                        'to' => $values->email,
                        'subject' => 'Aktivace účtu',
                        'email_template' => 'userActivation',
                        'body' => array(
                            'email' => $values->email,
                            'token' => $this->userRequest->add($userEmail->id, 'userActivation')->token,
                            'link' => $this->baseUrl. 'activate/account'
                        )
                    );
                    $this->emailNotification->send($param);
                }
    }


}
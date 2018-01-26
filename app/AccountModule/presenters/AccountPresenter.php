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
use App\Model\Users;

class AccountPresenter extends BasePresenter
{
    /** @var Users */
    private $users;
    public function __construct(Users $users)
    {
        $this->users = $users;
    }
    protected function createComponentSignupForm() {
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
        $form->addSubmit('signup','Registrovat');
        $form->onSuccess[] = [$this,'signupFormSucceeded'];
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
            'email' => $values->email
        ];

        $new_user = $this->users->register($data);
        if($new_user){
            $this->flashMessage('Váš účet byl úspěšně založen. Ověřte svůj účet pomoci emailu, který Vám právě přišel.','success');
            $this->redirect('Account:signin');
        } else {
            //Use Ajax to check if username exists
        }
    }

    protected function createComponentSigninForm()
    {
        $form = new Form;
        $form->addtext('username')
            ->setRequired('Prosím vyplňte uživatelské jméno');
        $form->addPassword('password')
            ->setRequired('Prosím vyplňte heslo');
        $form->addSubmit('signin','Přihlásit');
        $form->onSuccess[] = [$this,'signinFormSucceeded'];
        return $form;
    }

    public function signinFormSucceeded($form, $values)
    {
        try {
            $this->getUser()->login($values->username, $values->password);
            $this->redirect('Homepage:');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Přihlášení bylo neúspěšné. Zkontrolujte své přihlašovací údaje.');
        }
    }

    protected function createComponentSendPasswordLinkForm()
    {
        $form = new Form;
        $form->addEmail('email');
        $form->addSubmit('reset','Obnovit');
        $form->onSuccess[] = [$this,'sendPasswordLinkFormSucceded'];
        return $form;

    }

    public function sendPasswordLinkFormSucceded($form, $values)
    {
        $checkMail = $this->users->checkEmail($values->email);
        if ($checkMail) {
            $this->users->sendResetPasswordEmail($values->email);
            $this->flashMessage('Resetování bylo úspěšné. Právě jsme Vám poslali e-mail s odkazem na obnovení hesla.', 'success');
        } else {
            $this->flashMessage('E-mail, který jste zadali, nepatří k žádnému účtu', 'error');
        }
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
        $form->addSubmit('reset','Nastavit heslo');
        $form->addProtection();
        $form->onSuccess[] = [$this,'resetPasswordFormSucceded'];
        return $form;
    }

    public function resetPasswordFormSucceded($form, $values)
    {
        $data = array();
        $data['password'] = password_hash($values->password, PASSWORD_DEFAULT);

        if($this->users->updateUser($data))
        {
            $this->flashMessage('Vaše heslo bylo úspěšně změneno. Můžete se přihlásit novým heslem.', 'success');
        } else {
            $this->flashMessage('Nepodařilo se nastavit nové heslo', 'error');
        }
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->redirect('Account:signin');
    }

}
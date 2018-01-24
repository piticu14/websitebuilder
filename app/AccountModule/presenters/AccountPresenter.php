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
            ->addRule(Form::MAX_LENGTH, 'Uživatelské jméno mít nejvíce %d znaky', 100)
            ->setAttribute('placeholder','Uživatelské jméno');
        $form->addPassword('password')
            ->setRequired('password')
            ->setRequired('Prosím vyplňte heslo')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 5)
            ->setAttribute('placeholder','Heslo');
        $form->addPassword('password2')
            ->setRequired('Prosim vyplňte znovu heslo')
            ->setAttribute('placeholder', 'Heslo znovu:')
            ->addConditionOn($form['password'], Form::VALID)
            ->addRule(Form::EQUAL, 'Hesla se neshodují.', $form['password']);
        $form->addEmail('email')
            ->addRule(Form::EMAIL, 'Neplatná emailová adresa')
            ->setRequired('Prosím vyplňte emailovou adresu')
            ->addRule(Form::MAX_LENGTH, 'Emailová adresa může mít nejvíce %d znaky', 254)
            ->setAttribute('placeholder', 'Email:');
        $form->addSubmit('send','Registrovat')
            ->setAttribute('class','btn btn-primary signup');
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
            ->setRequired('Prosím vyplňte uživatelské jméno')
            ->setAttribute('placeholder', 'Uživatelské jméno:')
            ->setAttribute('class', 'form-control');
        $form->addPassword('password')
            ->setRequired('Prosím vyplňte heslo')
            ->setAttribute('placeholder', 'Heslo:')
            ->setAttribute('class', 'form-control');
        $form->addSubmit('send','Přihlásit')
            ->setAttribute('class', 'btn btn-primary signup');
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

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->redirect('Account:signin');
    }

}
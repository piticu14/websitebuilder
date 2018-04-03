<?php

use Nette\Application\UI\Form;

use App\Model\Users;

class SigninFormFactory
{
    /** @var Users */
    private $users;

    /** @var Nette\Application\UI\Presenter */
    private $presenter;

    /**
     * SigninFormFactory constructor.
     * @param Users $users
     * @param \Nette\Application\UI\Presenter $presenter
     */

    public function __construct(Users $users, Nette\Application\UI\Presenter $presenter)
    {
        $this->users = $users;
        $this->presenter = $presenter;
    }

    /**
     * @return Form
     */
    public function create()
    {
        $form = new Form;
        $form->addtext('username','Uživatelské jméno')
            ->setHtmlAttribute('placeholder','John Doe')
            ->setRequired('Prosím vyplňte uživatelské jméno');
        $form->addPassword('password', 'Heslo')
            ->setRequired('Prosím vyplňte heslo');
        $form->addSubmit('signin', 'Přihlásit');
        $form->onSuccess[] = [$this, 'signinFormSucceeded'];
        return $form;
    }

    public function signinFormSucceeded($form, $values)
    {
        try {
            $this->users->getUser()->login($values->username, $values->password);
            $this->presenter->redirect('Project:all');
        } catch (Nette\Security\AuthenticationException $e) {
            if($e->getCode() == 4) {
                    $this->presenter->flashMessage('Váš účet není aktivován. Aktivujte ho pomoci emailu, který jste obdržel');
                    $this->presenter->redirect('Account:signin');
            } else {
                $this->presenter->flashMessage('Přihlášení bylo neúspěšné. Zkontrolujte své přihlašovací údaje.','danger');
            }

        }
    }
}
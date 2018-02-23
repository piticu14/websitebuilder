<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 6.2.2018
 * Time: 19:19
 */

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
        $form->addtext('username')
            ->setHtmlAttribute('placeholder','John Doe')
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
            $this->users->getUser()->login($values->username, $values->password);
            if(!$this->users->isUserActive($this->users->getUser()->getIdentity()->email)) {
                $this->presenter->flashMessage('Váš účet není aktivován. Aktivujte ho pomoci emailu, který jste obdržel');
                $this->presenter->redirect('Account:signin');
            }
            $this->presenter->redirect('Dashboard:default');
        } catch (Nette\Security\AuthenticationException $e) {
            $this->presenter->flashMessage('Přihlášení bylo neúspěšné. Zkontrolujte své přihlašovací údaje.','danger');
        }
    }
}
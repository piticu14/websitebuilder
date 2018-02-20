<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 6.2.2018
 * Time: 20:13
 */

use Nette\Application\UI\Form;

use App\Model\Users;

class ResetPasswordFormFactory
{
    /** @var Users */
    private $users;

    /**
     * @var \Nette\Application\UI\Presenter
     */
    private $presenter;

    /**
     * ResetPasswordFormFactory constructor.
     * @param Users $users
     * @param \Nette\Application\UI\Presenter $presenter
     */
    public function __construct(Users $users, Nette\Application\UI\Presenter $presenter)
    {
        $this->users = $users;
        $this->presenter = $presenter;
    }

    public function create()
    {
        $form = new Form;
        $form->addPassword('password')
            ->setRequired('Prosím zadejte heslo')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 5);
        $form->addPassword('password2')
            ->setRequired('Prosim zadejte znovu heslo')
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

        if(isset($values->old_password)){
            $user = $this->users->getUserBy('id',$this->presenter->getUser()->id);
            if(!password_verify($values->old_password,$user->password)){
                $this->presenter->flashMessage('Vaše staré heslo je neplatné','danger');
                $this->presenter->redirect('this');
            }elseif($values->password == $values->old_password) {
                $this->presenter->flashMessage('Vaše nové heslo se shoduje s Vašim starým heslem. Zadejte prosím jiné heslo.','warning');
                $this->presenter->redirect('this');
            }
        }


        if ($this->users->updateUser($data)) {
            $this->presenter->flashMessage('Vaše heslo bylo úspěšně změneno. Můžete se přihlásit novým heslem.', 'success');
        } else {
            $this->presenter->flashMessage('Nepodařilo se nastavit nové heslo', 'warning');
        }
        $this->presenter->getUser()->logout();
        $this->presenter->redirect('Account:signin');
    }


}
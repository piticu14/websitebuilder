<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 6.2.2018
 * Time: 20:53
 */
use Nette\Application\UI\Form;

class EmailFormFactory
{
    public function create($emailRequiredText, $submitText)
    {
        $form = new Form;
        $form->addEmail('email','Email')
            ->setRequired($emailRequiredText)
            ->setHtmlAttribute('placeholder','váš@email.cz');
        $form->addSubmit('reset', $submitText);
        return $form;
    }
}
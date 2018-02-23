<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 6.2.2018
 * Time: 20:55
 */
use Nette\Application\UI\Form;

class SecurityQuestionFormFactory
{

    public function create()
    {
        $form = new Form;
        $form->addText('security_question');
        $form->addText('security_question_answer')
            ->setHtmlAttribute('placeholder','Vaše odpověď')
            ->setRequired('Odpovězte na bezpečnostní otázku');
        $form->addSubmit('reset', 'Obnovit');
        return $form;
    }
}
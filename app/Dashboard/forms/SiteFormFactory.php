<?php

use Nette\Application\UI\Form;

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 26.2.2018
 * Time: 14:44
 */
class SiteFormFactory
{

    public function create()
    {
        $form = new Form;
        $form->addText('title','Název webu')
            ->setRequired('Zadejte název webu')
            ->setHtmlAttribute('placeholder','mujweb')
            ->addRule(Form::MAX_LENGTH,'Název webu nesmi překročit %d znaků',45);
        $form->addText('subtitle','Podnázev webu')
            ->setRequired(false)
            ->setHtmlAttribute('placeholder','Můj nejlepší web')
            ->addRule(Form::MAX_LENGTH,'Podnázev webu nesmi překročit %d znaků',45);
        $form->addText('subdomain','Subdoména')
            ->setRequired('Zadejte název subdomény')
            ->setHtmlAttribute('placeholder','mojesubdomena')
            ->addRule(Form::MAX_LENGTH,'Název webu nesmi překročit %d znaků',20);
        $form->addCheckbox('active');
        $form->addSubmit('add','Uložit');

        return $form;
    }

}
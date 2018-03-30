<?php

use Nette\Application\UI\Form;

class ProjectFormFactory
{

    public function create()
    {
        $form = new Form;
        $form->addText('title','Titulek webu')
            ->setRequired('Zadejte titulek webu')
            ->setHtmlAttribute('placeholder','Fastweb')
            ->addRule(Form::MAX_LENGTH,'Titulek nesmi překročit %d znaků',45);
        $form->addText('subtitle','Podtitulek webu')
            ->setRequired(false)
            ->setHtmlAttribute('placeholder','Vaše aplikace pro tvorbu webobých stránek')
            ->addRule(Form::MAX_LENGTH,'Potitulek nesmi překročit %d znaků',255);
        $form->addText('subdomain','Subdoména')
            ->setRequired('Zadejte název subdomény')
            ->setHtmlAttribute('placeholder','mojesubdomena')
            ->addRule(Form::MAX_LENGTH,'Název subdomény nesmi překročit %d znaků',20);
        $form->addCheckbox('active');
        $form->addSubmit('add','Uložit');

        return $form;
    }

}
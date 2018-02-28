<?php

namespace App\Presenters;


use Nette;


class FrontBasePresenter extends Nette\Application\UI\Presenter
{


    public function formatLayoutTemplateFiles()
    {
        $layoutFiles = parent::formatLayoutTemplateFiles();
        $dir = dirname($this->getReflection()->getFileName()); # adresář aktuálního presenteru
        $layoutFiles[] = "$dir/../../presenters/templates/@front_layout.latte";
        return $layoutFiles;
    }



}

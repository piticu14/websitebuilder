<?php

namespace App\Presenters;


use Nette;


class BasePresenter extends Nette\Application\UI\Presenter
{


    public function formatLayoutTemplateFiles()
    {
        $layoutFiles = parent::formatLayoutTemplateFiles();
        $dir = dirname($this->getReflection()->getFileName()); # adresář aktuálního presenteru
        $layoutFiles[] = "$dir/../../presenters/templates/@layout.latte";
        return $layoutFiles;
    }



}

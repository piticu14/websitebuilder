<?php

namespace App\Presenters;


use Nette;

use App\Model\MediaManager;
use App\Model\TemplateManager;
use App\Model\PageManager;

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

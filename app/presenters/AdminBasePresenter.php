<?php

namespace App\Presenters;

use Nette;

class AdminBasePresenter extends Nette\Application\UI\Presenter
{
    public function beforeRender(){
        if(!$this->getUser()->isLoggedIn()) {
            $this->redirect("Account:signin");
        }
    }

    public function formatLayoutTemplateFiles()
    {
        $layoutFiles = parent::formatLayoutTemplateFiles();
        $dir = dirname($this->getReflection()->getFileName()); # adresář aktuálního presenteru
        $layoutFiles[] = "$dir/../../presenters/templates/@admin_layout.latte";
        return $layoutFiles;
    }
}
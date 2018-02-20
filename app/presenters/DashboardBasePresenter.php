<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 7.2.2018
 * Time: 20:44
 */

namespace App\Presenters;

use Nette;

class DashboardBasePresenter extends Nette\Application\UI\Presenter
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
        $layoutFiles[] = "$dir/../../presenters/templates/@dashboard_layout.latte";
        return $layoutFiles;
    }
}
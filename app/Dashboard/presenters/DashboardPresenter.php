<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 5.2.2018
 * Time: 17:03
 */

namespace App\Presenters;

use Nette;
use App\Model\DashboardManager;

class DashboardPresenter  extends Nette\Application\UI\Presenter
{
    /**
     * @var DashboardManager
     */
    private $dashboardManager;


    /**
     * DashboardPresenter constructor.
     * @param DashboardManager $dashboardManager
     */
    public function __construct(DashboardManager $dashboardManager)
    {
        $this->dashboardManager = $dashboardManager;
    }

    public function beforeRender(){
        if(!$this->getUser()->isLoggedIn()) {
            $this->redirect("Account:signin");
        }
    }

    public function renderDefault(){
        $this->template->templates = $this->dashboardManager->getTemplates();

}
    public function formatLayoutTemplateFiles()
    {
        $layoutFiles = parent::formatLayoutTemplateFiles();
        $dir = dirname($this->getReflection()->getFileName()); # adresář aktuálního presenteru
        $layoutFiles[] = "$dir/../../presenters/templates/@dashboard_layout.latte";
        return $layoutFiles;
    }
}
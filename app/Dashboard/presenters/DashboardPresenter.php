<?php

/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 5.2.2018
 * Time: 17:03
 */

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

use App\Model\DashboardManager;

class DashboardPresenter  extends DashboardBasePresenter
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

    public function renderDefault(){
        $this->template->templates = $this->dashboardManager->getTemplates();

}



}
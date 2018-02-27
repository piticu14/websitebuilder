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
use App\Model\SiteManager;

class DashboardPresenter  extends DashboardBasePresenter
{
    // TODO: create Site Modul and destroy Dashboard?
    /**
     * @var DashboardManager
     */
    private $dashboardManager;

    /**
     * @var SiteManager
     */
    private $siteManager;


    /**
     * DashboardPresenter constructor.
     * @param DashboardManager $dashboardManager
     * @param SiteManager $siteManager
     */
    public function __construct(DashboardManager $dashboardManager, SiteManager $siteManager)
    {
        $this->dashboardManager = $dashboardManager;
        $this->siteManager = $siteManager;
    }

    public function renderDefault(){
        $this->template->templates = $this->dashboardManager->getTemplates();

    }

    public function renderProjects()
    {
        $this->template->user_projects = $this->dashboardManager->getUserProjects();
    }

    protected function createComponentSiteForm()
    {
        $form = (new \SiteFormFactory())->create();
        $form->addHidden('template_id');
        $form->onSuccess[] = function (Form $form) {
            if($this->siteManager->subdomainDuplicate($form['subdomain']->value)){
                $nextId = $this->siteManager->getLastInsertedId() + 1;
                $form['subdomain']->value = $form['subdomain']->value . $nextId;
            }
            $this->siteManager->addSite($form->getValues());
            $this->flashMessage('Váš projekt byl uložen', 'success');
            $this->redirect('Dashboard:projects');
        };

        return $form;
    }


    public function renderAddSite($id){}



}
<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

use App\Model\projectManager;

class ProjectPresenter  extends AdminBasePresenter
{
    // TODO: create Site Module and destroy ProjectModule?
    /**
     * @var ProjectManager
     */
    private $projectManager;

    /**
     * DashboardPresenter constructor.
     * @param ProjectManager $projectManager
     */
    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;
    }
    protected function createComponentSiteForm()
    {
        $form = (new \ProjectFormFactory())->create();
        $form->addHidden('template_id');
        $form->onSuccess[] = function (Form $form) {
            if($this->projectManager->subdomainDuplicate($form['subdomain']->value)){
                $nextId = $this->projectManager->getLastInsertedId() + 1;
                $form['subdomain']->value = $form['subdomain']->value . $nextId;
            }
            $this->projectManager->addProject($form->getValues());
            $this->flashMessage('Váš projekt byl uložen', 'success');
            $this->redirect('Project:all');
        };

        return $form;
    }
    //ai nevoie si de Form? nu
    public function renderChooseTemplate(){
        $this->template->templates = $this->projectManager->getTemplates();

    }

    public function renderAll()
    {
        $this->template->user_projects = $this->projectManager->getUserprojects();
    }

    public function renderDefault($id)
    {
        $this->template->project = $this->projectManager->getProject($id);
    }





}
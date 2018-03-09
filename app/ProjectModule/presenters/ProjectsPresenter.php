<?php

namespace App\Presenters;

use App\Model\NavManager;
use App\Model\PageManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Finder;

use App\Model\projectManager;

class ProjectPresenter  extends AdminBasePresenter
{
    // TODO: create Site Module and destroy ProjectModule?
    /**
     * @var ProjectManager
     */
    private $projectManager;

    /**
     * @var NavManager;
     */
    private $navManager;

    /**
     * @var Pagemanager;
     */
    private $pageManager;
    /**
     * DashboardPresenter constructor.
     * @param ProjectManager $projectManager
     * @param NavManager $navManager
     * @param PageManager $pageManager
     */
    public function __construct(ProjectManager $projectManager, NavManager $navManager, PageManager $pageManager)
    {
        $this->projectManager = $projectManager;
        $this->navManager = $navManager;
        $this->pageManager = $pageManager;
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
    public function renderChooseTemplate(){
        $this->template->templates = $this->projectManager->getTemplates();

    }

    public function renderEdit($id)
    {

        $project = $this->projectManager->getProject($id);
        $jsFiles = array();
        $cssFiles = array();
        $dir = '../www/templates/' .$project->template_title .'/';
        foreach (Finder::findFiles('*.js')->exclude('*jquery.min*','*bootstrap.min*','jquery.js')->from($dir . 'js/') as $key => $file) {
            $jsFiles[] = $file->getBasename();
        }
        foreach (Finder::findFiles('*.css')->exclude('*jquery.min*','*bootstrap.min*')->from($dir . 'css/') as $key => $file) {
            $cssFiles[] = $file->getBasename();
        }
        $this->template->template_title = $project->template_title;
        $this->template->jsFiles = $jsFiles;
        $this->template->cssFiles = $cssFiles;
        $this->template->nav_items = $this->navManager->getNav($id);
        bdump($this->template->nav_items);

        $section = $this->getSession('project_pages');

        $project_pages = [
            array(
                'id' => 1,
                'name' => 'Project',
                'title' => 'Project',
                'keywords' => 'home, this, aaa',
                'content' => 'content of web1'
            ),
            array(
                'id' => 2,
                'name' => 'Our Studio',
                'title' => 'Our Studio',
                'keywords' => 'aaa, bbb, ccc',
                'content' => 'content of web2'
            ),
            array(
                'id' => 3,
                'name' => 'Blog',
                'title' => 'Blog',
                'keywords' => 'ddd, eee. fff',
                'content' => 'content of web3'
            ),
            array(
                'id' => 4,
                'name' => 'Contact',
                'title' => 'Contact',
                'keywords' => 'ggg, hhh, iii',
                'content' => 'content of web4'
            )
        ];
        $section->pages = $project_pages;
        $section->setExpiration(0);

    }

    public function renderAll()
    {
        $this->template->user_projects = $this->projectManager->getUserprojects();
    }

    public function renderDefault($id)
    {
        $this->template->project = $this->projectManager->getProject($id);
    }

    public function actionDelete($id)
    {
        $this->projectManager->deleteProject($id);
        $this->redirect('Project:all');
    }

}
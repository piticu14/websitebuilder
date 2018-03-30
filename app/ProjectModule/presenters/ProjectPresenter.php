<?php

namespace App\Presenters;

use App\Model\NavManager;
use App\Model\PageManager;
use App\Model\ProjectTempDataManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Finder;
use Nette\Utils\Json;
use Latte;

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
     * @var PageManager;
     */
    private $pageManager;

    /**
     * @var ProjectTempDataManager
     */
    private $projectTempDataManager;
    /**
     * DashboardPresenter constructor.
     * @param ProjectManager $projectManager
     * @param NavManager $navManager
     * @param PageManager $pageManager
     */
    public function __construct(ProjectManager $projectManager, NavManager $navManager, PageManager $pageManager, ProjectTempDataManager $projectTempDataManager)
    {
        $this->projectManager = $projectManager;
        $this->navManager = $navManager;
        $this->pageManager = $pageManager;
        $this->projectTempDataManager = $projectTempDataManager;
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
            $data = $form->getValues();
            $data['logo'] = '/websitebuilder/www/img/blank_logo.gif';
            $project = $this->projectManager->add($data);
            $this->projectTempDataManager->add($data, $project->id);

            $path = 'user_images/' . $project->id . '/images/';
            if(!file_exists($path)) mkdir($path,'0777',true);

            $this->flashMessage('Váš projekt byl uložen', 'success');
            $this->redirect('Project:all');
        };

        return $form;
    }
    public function renderChooseTemplate(){
        $this->template->templates = $this->projectManager->getTemplates();

    }

    public function renderEdit($id,$page_id)
    {

        $project = $this->projectManager->get($id);
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
        $this->template->nav_items = $this->navManager->get($id,0);
        $this->template->current_page = $this->pageManager->get($page_id);
        $this->template->user_images = $this->getUserImages($id);
        $this->template->projectTempData = $this->projectTempDataManager->get($id);

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

        /*
        $latte = new Latte\Engine;

        $html = $latte->renderToString(__DIR__ . '/../templates/Project/magnet/index.latte');
        bdump($html);
        */

    }

    public function renderAll()
    {
        $user_projects = $this->projectManager->getAll();
        $first_projects_pages = array();
        foreach ($user_projects as $user_project) {
            $first_projects_pages[] = $this->pageManager->first($user_project->id);

        }
        bdump($user_projects);
        $this->template->user_projects = $user_projects;
        $this->template->first_projects_pages = $first_projects_pages;
        //$this->template->first_page = $this->
    }

    /*
    public function renderDefault($id)
    {
        $this->template->project = $this->projectManager->getProject($id);
    }
    */

    public function actionDelete($id)
    {
        $path = 'user_images/' . $id;
        $this->projectManager->delete($id);
        $this->projectTempDataManager->delete($id);
        $this->projectManager->deleteFolder($path);
        $this->redirect('Project:all');
    }

    private function getUserImages($id)
    {
        $masks =['*.jpg','*.png','*.gif','*.jpeg'];
        $dir = '../www/user_images/' . $id .'/images/';
        $images = array();
        foreach (Finder::findFiles($masks)
                     ->in($dir) as $file) {
            $images[] = 'user_images/' . $id . '/images/' . $file->getBasename();

        }
        return $images;
    }

    public function handleAddImages()
    {

        if($this->isAjax()) {
            $files = $this->getHttpRequest()->getFiles();
            $filesNames = array();
            foreach($files as $file) {
                if( $file->isOk() and $file->isImage() ) {
                    $imageName = $file->getSanitizedName();
                    $file->move('user_images/' . $this->getParameter('id') . '/images/' . $imageName);
                    $filesNames[] = $imageName;
            }
        }
            $this->template->user_images = $this->getUserImages($this->getParameter('id'));


            //$this->payload->images = $this->getUserImages($this->getParameter('id'));
        }

        $this->redrawControl('wrapper');
        $this->redrawControl('userImages');

    }
/*
    public function handleGetImageJsonList(){
        $user_images = $this->getUserImages($this->getParameter('id'));
        $json_Image_list = array();
        foreach ($user_images as $id => $image) {
            $json_Image_list[] = array(
                'image' => $this->getHttpRequest()->getUrl()->getBasePath() . $image
            );
        }
        $this->sendJson($json_Image_list);
    }
*/


    public function handlePublish($data){

    }

    public function handleSaveTemporary($nav, $logo, $body)
    {
        $nav_array = Json::decode($nav, Json::FORCE_ARRAY);
        foreach ($nav_array as $sort_order => $nav){
            //$this->pageManager->update($nav, $nav['page_id'],0);

            $nav['sort_order'] = $sort_order;
            $this->navManager->update($nav,$nav['page_id'],0);
        }
        $logo_array = Json::decode($logo, Json::FORCE_ARRAY);
        $this->projectTempDataManager->update($logo_array, $this->getParameter('id'));

    }
}
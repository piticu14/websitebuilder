<?php

namespace App\Presenters;

use App\Model\HeaderManager;
use App\Model\PageItemManager;
use App\Model\FooterManager;
use App\Model\NavManager;
use App\Model\PageManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Finder;
use Nette\Utils\Json;
use Latte;

use App\Model\projectManager;

class ProjectPresenter  extends AdminBasePresenter
{
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
     * @var HeaderManager
     */
    private $headerManager;

    /**
     * @var FooterManager
     */
    private $footerManager;

    /**
     * @var PageItemManager
     */
    private $pageItemManager;

    /**
     * DashboardPresenter constructor.
     * @param ProjectManager $projectManager
     * @param NavManager $navManager
     * @param PageManager $pageManager
     * @param HeaderManager $headerManager
     * @param FooterManager $footerManager
     * @param PageItemManager $pageItemManager
     */
    public function __construct(ProjectManager $projectManager,
                                NavManager $navManager,
                                PageManager $pageManager,
                                HeaderManager $headerManager,
                                FooterManager $footerManager,
                                PageItemManager $pageItemManager)
    {
        $this->projectManager = $projectManager;
        $this->navManager = $navManager;
        $this->pageManager = $pageManager;
        $this->headerManager = $headerManager;
        $this->footerManager = $footerManager;
        $this->pageItemManager = $pageItemManager;
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
            $data['logo'] = JSON::encode([
                'type' => 'img',
                'src' => '/websitebuilder/www/img/blank_logo.gif'
            ]);
            $project = $this->projectManager->add($data);
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

    public function actionEdit($id,$page_id)
    {
        $this->template->gallery_images = [];
    }
    public function renderEdit($id,$page_id)
    {
        $this->initTemplateVariables($id,$page_id,0);


        /*
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
        */

        /*
        $latte = new Latte\Engine;

        $html = $latte->renderToString(__DIR__ . '/../templates/Project/magnet/index.latte');
        bdump($html);
        */

    }

    private function initTemplateVariables($id,$page_id,$publish)
    {
        $project = $this->projectManager->get($id);
        $jsFiles = array();
        $cssFiles = array();
        $dir = '../www/templates/' .$project->template_title .'/';
        foreach (Finder::findFiles('*.js')->exclude('*jquery.min*','*bootstrap.min*','jquery.js','bootstrap.js','jquery*','google_map.js')->from($dir . 'js/') as $key => $file) {
            $jsFiles[] = $file->getBasename();
        }
        foreach (Finder::findFiles('*.css')->exclude('*jquery.min*','*bootstrap.min*','bootstrap.css')->from($dir . 'css/') as $key => $file) {
            $cssFiles[] = $file->getBasename();
        }
        $this->template->template_title = $project->template_title;

        $this->template->jsFiles = $jsFiles;
        $this->template->cssFiles = $cssFiles;

        $this->template->nav_items = $this->navManager->get($id,$publish);
        $this->template->current_page = $this->pageManager->get($page_id);

        $this->template->user_images = $this->getUserImages($id);

        $this->template->header = $this->headerManager->get($id,$publish);
        $this->template->header_logo = JSON::decode($this->template->header->logo,Json::FORCE_ARRAY);

        $this->template->footer = $this->footerManager->get($id,$publish);
        $this->template->social_media = JSON::decode($this->template->footer->social_media,JSON::FORCE_ARRAY);
        bdump($this->template->social_media);


        $this->template->page_items = $this->pageItemManager->getAll($page_id,$publish);

        $this->template->first_page = $this->pageManager->first($id);

        $ids = array();
        foreach($this->template->page_items as $page_item){
            if($page_item->additional != ''){
                $photogalleryIds = JSON::decode($page_item->additional,JSON::FORCE_ARRAY);

                foreach($photogalleryIds as $pg_ids) {
                    foreach($pg_ids as $pg_id){
                        $ids[] = $pg_id;

                    }
                }
            }
        }

        $this->template->photogalleryIds = $ids;

    }

    public function renderAll()
    {
        $user_projects = $this->projectManager->getAll();
        $first_projects_pages = array();
        foreach ($user_projects as $user_project) {
            $first_projects_pages[] = $this->pageManager->first($user_project->project_id);


        }
        $this->template->user_projects = $user_projects;
        $this->template->first_projects_pages = $first_projects_pages;
    }


    public function actionDelete($id)
    {
        $path = 'user_images/' . $id;
        $this->projectManager->delete($id);
        //$this->projectTempDataManager->delete($id);
        //$this->projectManager->deleteFolder($path);
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

    public function getPhotogalleryImages($id,$pg_path)
    {
        $masks =['*.jpg','*.png','*.gif','*.jpeg'];
        $dir = '../www/user_images/' . $id . '/' . $pg_path . '/';
        $images = array();
        $path = 'user_images/' . $id . '/'. $pg_path  . '/';
        if(!file_exists($path)) mkdir($path,'0777',true);
        foreach (Finder::findFiles($masks)
                     ->in($dir) as $file) {
            $images[] = $this->getHttpRequest()->getUrl()->basePath. $path . $file->getBasename();

        }
        return $images;
    }

    public function handleLoadPhotogalleryImages($pgPath) {
        if($this->isAjax()){
            $this->template->gallery_images = $this->getPhotogalleryImages($this->getParameter('id'),$pgPath);
            //bdump($this->template->gallery_images);
        }
        $this->redrawControl('wrapper');
        $this->redrawControl('photogalleryImages');
    }

    public function handleAddImages()
    {

        if($this->isAjax()) {
            $path = 'user_images/' . $this->getParameter('id') . '/' . $this->getHttpRequest()->getPost('type') . '/';
            if(!file_exists($path)) mkdir($path,'0777',true);

            $files = $this->getHttpRequest()->getFiles();
            $filesNames = array();
            foreach($files as $file) {
                if( $file->isOk()) {
                    $fileName = $file->getSanitizedName();
                    $file->move($path . $fileName);
                    $filesNames[] = $fileName;
            }
        }
            $this->template->user_images = $this->getUserImages($this->getParameter('id'));
            $this->template->gallery_images = $this->getPhotogalleryImages($this->getParameter('id'),$this->getHttpRequest()->getPost('type'));



            $this->payload->src = $this->getHttpRequest()->getUrl()->basePath . $path . $filesNames[0];
            //$this->payload->images = $this->getUserImages($this->getParameter('id'));
        }

        $this->redrawControl('wrapper');
        $this->redrawControl('userImages');
        $this->redrawControl('photogalleryImages');

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


    public function actionPublish($project_id,$pid)
    {
        $this->pageManager->publish($project_id);
        $this->headerManager->publish($project_id);
        $this->navManager->publish($project_id);
        $this->pageItemManager->publish($project_id);
        $this->footerManager->publish($project_id);



        $this->flashMessage('Publikování proběhlo úspěšně.', 'success');
        $this->redirect('Project:edit', array("id" => $project_id, 'page_id' => $pid));

    }

    public function handleSaveTemporary($nav, $logo, $body, $footer)
    {
       $body_array = Json::decode($body,Json::FORCE_ARRAY);
        foreach($body_array as $order_on_page => $body) {
            $body['order_on_page'] = $order_on_page;
            if(isset($body['id'])){
                $this->pageItemManager->update($body['id'],$body,0);
            }else{
                $this->pageItemManager->add($this->getParameter('page_id'),$body,0);
            }

            //$this->pageItemManager->add($this->getParameter('page_id'),$body,1);
        }


        $nav_array = Json::decode($nav, Json::FORCE_ARRAY);
        foreach ($nav_array as $sort_order => $nav){
            $nav['sort_order'] = $sort_order;
            if(is_numeric($nav['id'])){
                $this->navManager->update($nav,$nav['page_id'],0);
                $this->pageManager->update($nav,$nav['page_id']);
            }else{
                $nav['project_id'] = $this->getParameter('id');
                $nav['new'] = true;

                $newPage = $this->pageManager->add(Nette\Utils\ArrayHash::from($nav));
                $nav['page_id'] = $newPage->temp_id;
                $this->navManager->add(Nette\Utils\ArrayHash::from($nav),0);

                /*
                $nav['page_id'] = $newPage->publish_id;
                $this->navManager->add(Nette\Utils\ArrayHash::from($nav),1);
                */
            }

        }

        $logo_array = Json::decode($logo, Json::FORCE_ARRAY);
        $this->headerManager->update($logo_array,$this->getParameter('id'),0);

        $footer_array =  Json::decode($footer, Json::FORCE_ARRAY);
        $this->footerManager->update($this->getParameter('id'),$footer_array,0);

        $this->projectManager->updateTime($this->getParameter('id'));

    }

    public function handleDeletePageItem($item_id)
    {
        $this->pageItemManager->delete($item_id);

    }

    public function renderShow($id,$page_id)
    {
        $project = $this->projectManager->get($id);
        if(!$project->active){
            $this->setView('../../../../app/presenters/templates/Error/notActive');
        }
        $this->initTemplateVariables($id,$page_id,1);
    }



}
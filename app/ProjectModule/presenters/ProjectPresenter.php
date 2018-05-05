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

class ProjectPresenter extends AdminBasePresenter
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
            if ($this->projectManager->subdomainDuplicate($form['subdomain']->value)) {
                $nextId = $this->projectManager->getLastInsertedId() + 1;
                $form['subdomain']->value = $form['subdomain']->value . $nextId;
            }
            $data = $form->getValues();
            $data['logo'] = JSON::encode([
                'type' => 'img',
                'src' => '/websitebuilder/www/img/blank_logo.jpg'
            ]);
            $project = $this->projectManager->add($data);
            $path = 'user_images/' . $project->subdomain . '/images/';
            if (!file_exists($path)) mkdir($path, '0777', true);

            $this->flashMessage('Váš projekt byl uložen', 'success');
            $this->redirect('Project:all');
        };

        return $form;
    }


    public function renderChooseTemplate()
    {
        $this->template->templates = $this->projectManager->getTemplates();

    }

    public function actionEdit($subdomain, $page_url)
    {
        $this->template->gallery_images = [];
    }

    public function renderEdit($subdomain, $page_url)
    {
        $project = $this->projectManager->getBy('subdomain', $subdomain);
        if ($project) {
                $page = $this->pageManager->getByUrl($project->id,$page_url, 0);
                if ($page) {
                    bdump('aaaa');
                    $this->initTemplateVariables($project, $page->id, 0);
                }else{
                    bdump('bbbb');
                }
        }


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

    private function initTemplateVariables($project, $page_id, $publish)
    {


        $jsFiles = array();
        $cssFiles = array();
        $dir = '../www/templates/' . $project->template_title . '/';
        foreach (Finder::findFiles('*.js')->exclude('*jquery.min*', '*bootstrap.min*', 'jquery.js', 'bootstrap.js', 'jquery*', 'google_map.js')->from($dir . 'js/') as $key => $file) {
            $jsFiles[] = $file->getBasename();
        }
        foreach (Finder::findFiles('*.css')->exclude('*jquery.min*', '*bootstrap.min*', 'bootstrap.css', 'default.css')->from($dir . 'css/') as $key => $file) {
            $cssFiles[] = $file->getBasename();
        }
        $this->template->template_title = $project->template_title;

        $this->template->jsFiles = $jsFiles;
        $this->template->cssFiles = $cssFiles;


        $this->template->nav_items = $this->navManager->get($project->id, $publish);
        $this->template->current_page = $this->pageManager->get($page_id);

        $this->template->user_images = $this->getUserImages($project->subdomain);

        $this->template->header = $this->headerManager->get($project->id, $publish);
        $this->template->header_logo = JSON::decode($this->template->header->logo, Json::FORCE_ARRAY);

        $this->template->footer = $this->footerManager->get($project->id, $publish);
        $this->template->social_media = JSON::decode($this->template->footer->social_media, JSON::FORCE_ARRAY);


        $this->template->page_items = $this->pageItemManager->getAll($page_id, $publish);


        $this->template->first_page = $this->pageManager->first($project->id);

        $ids = array();
        foreach ($this->template->page_items as $page_item) {
            if ($page_item->additional != '') {
                $photogalleryIds = JSON::decode($page_item->additional, JSON::FORCE_ARRAY);

                foreach ($photogalleryIds as $pg_ids) {
                    foreach ($pg_ids as $pg_id) {
                        $ids[] = $pg_id;

                    }
                }
            }
        }

        $this->template->photogalleryIds = $ids;

    }

    public function renderAll()
    {
        /** TODO: Public link first page_url shows temp page_url. If page_url changed but not published ==> error*/
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
        $masks = ['*.jpg', '*.png', '*.gif', '*.jpeg'];
        $dir = '../www/user_images/' . $id . '/images/';
        $images = array();
        foreach (Finder::findFiles($masks)
                     ->in($dir) as $file) {
            $images[] = 'user_images/' . $id . '/images/' . $file->getBasename();

        }
        return $images;
    }

    public function getPhotogalleryImages($id, $pg_path)
    {
        $masks = ['*.jpg', '*.png', '*.gif', '*.jpeg'];
        $dir = '../www/user_images/' . $id . '/' . $pg_path . '/';
        $images = array();
        $path = 'user_images/' . $id . '/' . $pg_path . '/';
        if (!file_exists($path)) mkdir($path, '0777', true);
        foreach (Finder::findFiles($masks)
                     ->in($dir) as $file) {
            $images[] = $this->getHttpRequest()->getUrl()->basePath . $path . $file->getBasename();

        }
        return $images;
    }

    public function handleLoadPhotogalleryImages($pgPath)
    {
        if ($this->isAjax()) {
            $this->template->gallery_images = $this->getPhotogalleryImages($this->getParameter('subdomain'), $pgPath);
            //bdump($this->template->gallery_images);
        }
        $this->redrawControl('wrapper');
        $this->redrawControl('photogalleryImages');
    }

    public function handleAddImages()
    {

        if ($this->isAjax()) {
            $path = 'user_images/' . $this->getParameter('subdomain') . '/' . $this->getHttpRequest()->getPost('type') . '/';
            if (!file_exists($path)) mkdir($path, '0777', true);

            $files = $this->getHttpRequest()->getFiles();
            $filesNames = array();
            foreach ($files as $file) {
                if ($file->isOk()) {
                    $fileName = $file->getSanitizedName();
                    $file->move($path . $fileName);
                    $filesNames[] = $fileName;
                }
            }
            $this->template->user_images = $this->getUserImages($this->getParameter('subdomain'));
            $this->template->gallery_images = $this->getPhotogalleryImages($this->getParameter('subdomain'), $this->getHttpRequest()->getPost('type'));


            $this->payload->src = $this->getHttpRequest()->getUrl()->basePath . $path . $filesNames[0];
            //$this->payload->images = $this->getUserImages($this->getParameter('id'));
        }

        $this->redrawControl('wrapper');
        $this->redrawControl('userImages');
        $this->redrawControl('userImages1');
        $this->redrawControl('userImages2');
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


    public function actionPublish($subdomain, $page_url)
    {
        $project = $this->projectManager->getBy('subdomain',$subdomain);
        $this->pageManager->publish($project->id);
        $this->headerManager->publish($project->id);
        $this->navManager->publish($project->id);
        $this->pageItemManager->publish($project->id);
        $this->footerManager->publish($project->id);


        $this->flashMessage('Publikování proběhlo úspěšně.','success');
        $this->redirect('Project:edit', array("subdomain" => $subdomain, 'page_url' => $page_url));

    }

    public function handleSaveTemporary($nav, $header, $body, $footer, $current_page_url)
    {

        $project = $this->projectManager->getBy('subdomain',$this->getParameter('subdomain'));

        $nav_array = Json::decode($nav, Json::FORCE_ARRAY);
        foreach ($nav_array as $sort_order => $nav) {

            $nav['sort_order'] = $sort_order;
            if (is_numeric($nav['id'])) {
                $this->navManager->update($nav, $nav['page_id'], 0);
                $this->pageManager->update($nav, $nav['page_id']);
            } else {
                $nav['project_id'] = $project->id;

                $newPage = $this->pageManager->add(Nette\Utils\ArrayHash::from($nav));
                $nav['page_id'] = $newPage->temp_id;
                $this->navManager->add(Nette\Utils\ArrayHash::from($nav), 0);

                /*
                $nav['page_id'] = $newPage->publish_id;
                $this->navManager->add(Nette\Utils\ArrayHash::from($nav),1);
                */
            }

        }


        $header_array = Json::decode($header, Json::FORCE_ARRAY);
        $this->headerManager->update($header_array, $project->id, 0);

        $body_array = Json::decode($body, Json::FORCE_ARRAY);
        foreach ($body_array as $order_on_page => $body) {
            $body['order_on_page'] = $order_on_page;
            if (isset($body['id'])) {
                $this->pageItemManager->update($body['id'], $body, 0);
            } else {
                $page = $this->pageManager->getByUrl($project->id, $this->getParameter('page_url'),0);
                $this->pageItemManager->add($page->id, $body, 0);
            }

            //$this->pageItemManager->add($this->getParameter('page_id'),$body,1);
        }

        $footer_array = Json::decode($footer, Json::FORCE_ARRAY);
        $this->footerManager->update($project->id, $footer_array, 0);

        $this->projectManager->updateTime($project->id);
    }

    public function handleDeletePageItem($item_id)
    {
        $this->pageItemManager->delete($item_id);

    }

    public function handleDeletePage($pid)
    {
        $this->pageManager->delete($pid);


    }

    public function renderShow($subdomain, $page_url)
    {
        $project = $this->projectManager->getBy('project.subdomain', $subdomain);
        if ($project) {
            if (!$project->active) {
                $this->setView('../../../../app/presenters/templates/Error/notActive');
            } else {
                $page = $this->pageManager->getByUrl($project->id,$page_url,1);
                if ($page) {
                    $this->initTemplateVariables($project, $page->id, 1);
                }
            }
        }
    }


}
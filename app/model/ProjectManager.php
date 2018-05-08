<?php

namespace App\Model;

use Nette;
use Nette\Utils\Strings;
use Nette\Utils\Json;


class ProjectManager extends BaseManager
{

    private static $table = 'project';

    /**
     * @var PageManager
     */
    private $pageManager;

    /**
    * @var NavManager;
    */
    private $navManager;

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
     * ProjectManager constructor.
     * @param Nette\Database\Context $database
     * @param Nette\Security\User $user
     * @param PageManager $pageManager
     * @param NavManager $navManager
     * @param HeaderManager $headerManager
     * @param FooterManager $footerManager
     * @param PageItemManager $pageItemManager
     */

    public function __construct(Nette\Database\Context $database,
                                Nette\Security\User $user,
                                PageManager $pageManager,
                                NavManager $navManager,
                                HeaderManager $headerManager,
                                FooterManager $footerManager,
                                PageItemManager $pageItemManager)
    {
        parent::__construct($database,$user);
        $this->pageManager = $pageManager;
        $this->navManager = $navManager;
        $this->headerManager = $headerManager;
        $this->footerManager = $footerManager;
        $this->pageItemManager = $pageItemManager;
    }

    public function getTemplates(){
        return $this->getDatabase()->table('template')->fetchAll();
    }


    public function getAll()
    {
        $projects = $this->getDatabase()->table(self::$table)
            ->select('project.*,template.title AS template_title')
            ->where('deleted_at',NULL)
            ->where('project.user_id',$this->getUser()->id)->fetchAll();


        /*
        $data = array();

        foreach($projects as $project) {
            bdump($project->template->title);
            $data[] = $project->related('header')
                ->where('project_id',$project->id)->fetch();
        }

        bdump(array_merge($data));
        die();
        */

        $data = $this->getDatabase()->table('header')
            ->select('project.*,header.*')
        ->where('header.project_id',$projects)
            ->where('publish',0)->fetchAll();



        return $data;
    }
    public function add($data)
    {
        $project = $this->getDatabase()->table(self::$table)->insert([
            'template_id' => $data->template_id,
            'user_id' => $this->getUser()->id,
            'subdomain' => Strings::lower(Strings::webalize($data->subdomain)),
            'active' => $data->active
        ]);

        $this->init($data,$project->id);

        return $project;

    }

    private function init($data, $project_id)
    {
        $nav_titles = array('Úvod','Galerie','O nás','Kontakt');

        foreach($nav_titles as $key => $title) {
            $page_data = array(
                'project_id' => $project_id,
                'title' => $title,
                'url' => Strings::webalize(Strings::lower($title)),
            );
            $page_hash = Nette\Utils\ArrayHash::from($page_data);
            $page_relationship = $this->pageManager->add($page_hash);


            $nav_data = array(
                'project_id' => $project_id,
                'page_id' => $page_relationship->publish_id,
                'text' => $title,
                'sort_order' => $key
            );
            $nav_hash = Nette\Utils\ArrayHash::from($nav_data);


            $nav_temp_data = array(
                'project_id' => $project_id,
                'page_id' => $page_relationship->temp_id,
                'text' => $title,
                'sort_order' => $key
            );
            $nav_temp_hash = Nette\Utils\ArrayHash::from($nav_temp_data);
            $this->navManager->add($nav_temp_hash,0);
            $this->navManager->add($nav_hash,1);

            $default_item_content = '<section id="page_item" style="text-align:center"><div class="row"><div class="col-sm-12" data-type="container-content">
        <section data-type="component-text">
        <p>Zde upravujte obsah Vašeho webu.</p>
        </section></div></div></section>';


            $page_item_temp = array(
                'content' => $default_item_content,
                'order_on_page' => 0,
            );

            $this->pageItemManager->add($page_relationship->temp_id,$page_item_temp,0);


        }
        $this->headerManager->add($data,$project_id,0);
        $this->headerManager->add($data,$project_id,1);


        $social_media = array(
            [
                'media' => 'Facebook',
                'active' => 1,
                'href' => 'javascript:void(0)'
            ],
            [
                'media' => 'Twitter',
                'active' => 1,
                'href' => 'javascript:void(0)'
            ],
            [
                'media' => 'LinkedIn',
                'active' => 1,
                'href' => 'javascript:void(0)'
            ],
            [
                'media' => 'Instagram',
                'active' => 1,
                'href' => 'javascript:void(0)'
            ]);

        $footer_data = array(
            'content' => '<p>© 2018 QuickWeb | Všechna práva vyhrazena.</p>',
            'social_media' => Json::encode($social_media)
        );

        $this->footerManager->add($project_id,$footer_data,0);
        $this->footerManager->add($project_id,$footer_data,1);
    }


    public function subdomainDuplicate($subdomain)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('subdomain',$subdomain)
            ->count();
    }

    public function getLastInsertedId()
    {
        return $this->getDatabase()->table(self::$table)
            ->order('id DESC')
            ->limit(1)
            ->fetch()->id;
    }

    public function getBy($column,$value)
    {
        return $this->getDatabase()->table(self::$table)
            ->select('project.*,template.title AS template_title')
            ->where($column,$value)
            ->fetch();
    }



    public function patch($data)
    {

        $this->getDatabase()->table(self::$table)
            ->where('id',$data->id)
            ->update($data);
    }

    public function delete($subdomain)
    {

        $this->getDatabase()->table(self::$table)
            ->where('subdomain',$subdomain)
            ->update([
                'deleted_at' => date("Y-m-d H:i:s"),
            ]);
    }

    public function updateTime($id)
    {
        $this->getDatabase()->table(self::$table)
            ->where('id',$id)
            ->update([
                'updated_at' => date("Y-m-d H:i:s")
            ]);
    }


}
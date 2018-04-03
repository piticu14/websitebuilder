<?php

namespace App\Model;

use Nette;
use Nette\Utils\Strings;
use Nette\Utils\Json;


class ProjectManager extends BaseManager
{
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
     * ProjectManager constructor.
     * @param Nette\Database\Context $database
     * @param Nette\Security\User $user
     * @param PageManager $pageManager
     * @param NavManager $navManager
     * @param HeaderManager $headerManager
     */

    public function __construct(Nette\Database\Context $database,
                                Nette\Security\User $user,
                                PageManager $pageManager,
                                NavManager $navManager,
                                HeaderManager $headerManager)
    {
        parent::__construct($database,$user);
        $this->pageManager = $pageManager;
        $this->navManager = $navManager;
        $this->headerManager = $headerManager;
    }

    public function getTemplates(){
        return $this->getDatabase()->table('template')->fetchAll();
    }


    public function getAll()
    {
        $projects = $this->getDatabase()->table('project')
            ->select('project.*,template.title AS template_title')
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
        $project = $this->getDatabase()->table('project')->insert([
            'template_id' => $data->template_id,
            'user_id' => $this->getUser()->id,
            'subdomain' => Strings::trim($data->subdomain),
            'active' => $data->active
        ]);

        $this->init($data,$project->id);

        return $project;

    }

    private function init($data, $project_id)
    {
        $nav_titles = array('Item1','Item2','Item3','Item4');

        foreach($nav_titles as $key => $title) {
            $page_data = array(
                'project_id' => $project_id,
                'title' => $title,
            );
            $page_hash = Nette\Utils\ArrayHash::from($page_data);
            $page = $this->pageManager->add($page_hash);


            $nav_data = array(
                'page_id' => $page->id,
                'title' => $title,
                'url' => Strings::lower($title),
                'sort_order' => $key
            );
            $nav_hash = Nette\Utils\ArrayHash::from($nav_data);

            $this->navManager->add($nav_hash,0);
            $this->navManager->add($nav_hash,1);

        }

        $this->headerManager->add($data,$project_id,0);
        $this->headerManager->add($data,$project_id,1);

        /** TODO: Footer */
    }


    public function subdomainDuplicate($subdomain)
    {
        return $this->getDatabase()->table('project')
            ->where('subdomain',$subdomain)
            ->count();
    }

    public function getLastInsertedId()
    {
        return $this->getDatabase()->table('project')
            ->order('id DESC')
            ->limit(1)
            ->fetch()->id;
    }

    public function get($id)
    {
        return $this->getDatabase()->table('project')
            ->select('project.*,template.title AS template_title')
            ->where('project.id',$id)
            ->fetch();
    }

    public function patch($data)
    {

        $this->getDatabase()->table('project')->where('id',$data->id)->update($data);
    }

    public function delete($id)
    {

        $this->navManager->delete($id);
        $this->pageManager->deleteAll($id);
        $this->getDatabase()->table('project')
            ->where('id',$id)
            ->delete();
    }


}
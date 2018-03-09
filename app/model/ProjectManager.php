<?php

namespace App\Model;

use Nette;
use Nette\Utils\Strings;


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

    public function __construct(Nette\Database\Context $database, Nette\Security\User $user,PageManager $pageManager, NavManager $navManager)
    {
        parent::__construct($database,$user);
        $this->pageManager = $pageManager;
        $this->navManager = $navManager;
    }

    public function getTemplates(){
        return $this->getDatabase()->table('template')->fetchAll();
    }


    public function getUserProjects()
    {
        return $this->getDatabase()->table('project')
            ->select('template.title AS template_title,project.*')
        ->where('user_id',$this->getUser()->id)->fetchAll();
    }
    public function addProject($data)
    {
        $data['user_id'] = $this->getUser()->id;
        $data['subdomain'] = Strings::trim($data['subdomain']);
        $project = $this->getDatabase()->table('project')->insert($data);

        $nav_titles = array('Item1','Item2','Item3','Item4');
        $this->init_project($nav_titles,$project->id);

    }

    private function init_project($nav_titles, $project_id)
    {
        foreach($nav_titles as $key => $title) {
            $page_data = array(
                'project_id' => $project_id,
                'title' => $title,
            );
            $page_hash = Nette\Utils\ArrayHash::from($page_data);
            $page = $this->pageManager->addPage($page_hash);


            $nav_data = array(
                'project_id' => $project_id,
                'page_id' => $page->id,
                'title' => $title,
                'url' => Strings::lower($title),
                'sort_order' => $key
            );
            $nav_hash = Nette\Utils\ArrayHash::from($nav_data);
            $this->navManager->addNav($nav_hash);

        }
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

    public function getProject($id)
    {
        return $this->getDatabase()->table('project')
            ->select('project.*,template.title AS template_title')
            ->where('project.id',$id)
            ->fetch();
    }

    public function patch($data)
    {

        $this->getDatabase()->table('project')->where('id',$data->id)->update([
            'active' => $data->active,
            'subdomain' => $data->subdomain]);
    }

    public function deleteProject($id)
    {

        $this->navManager->deleteNav($id);
        $this->pageManager->deleteAllPages($id);
        $this->getDatabase()->table('project')
            ->where('id',$id)
            ->delete();
    }


}
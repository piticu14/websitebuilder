<?php

namespace App\Model;

use Nette;
use Nette\Utils\Strings;


class ProjectManager extends BaseManager
{

    public function getTemplates(){
        return $this->getDatabase()->table('template')->fetchAll();
    }


    public function getUserProjects()
    {
        return $this->getDatabase()->table('project')
            ->select('template.title AS template_title,project.*')
            ->where('user_id',$this->getUser()->id)
            ->fetchAll();
    }
    public function addProject($data)
    {
        $data['user_id'] = $this->getUser()->id;
        $data['subdomain'] = Strings::trim($data['subdomain']);
        return $this->getDatabase()->table('project')->insert($data);
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



}
<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 5.2.2018
 * Time: 21:22
 */

namespace App\Model;

use Nette;


class DashboardManager extends BaseManager
{

    public function getTemplates(){
        return $this->getDatabase()->table('template')->fetchAll();
    }


    public function getUserProjects()
    {
        return $this->getDatabase()->table('site')
            ->select('template.title AS template_title,site.*')
            ->where('user_id',$this->getUser()->id)
            ->fetchAll();
    }

}
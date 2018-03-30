<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 7.3.2018
 * Time: 11:43
 */

namespace App\Model;

use Nette;

class PageManager extends BaseManager
{
    public function getAll($project_id)
    {
        return $this->getDatabase()->table('page')
            ->where('id',$project_id)
            ->fetchAll();
    }

    public function add($data)
    {
        return $this->getDatabase()->table('page')
            ->insert([
                'project_id' => $data->project_id,
                'title' => $data->title,
            ]);
    }


    public function deleteAll($project_id)
    {
        return $this->getDatabase()->table('page')
            ->where('project_id',$project_id)
            ->delete();
    }

    public function first($project_id)
    {
        return $this->getDatabase()->table('page')
            ->where('project_id',$project_id)
            ->limit(1)
            ->fetch();
    }

}
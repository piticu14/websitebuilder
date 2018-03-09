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
    public function getAllPages($project_id)
    {
        return $this->getDatabase()->table('page')
            ->where('id',$project_id)
            ->fetchAll();
    }

    public function addPage($data)
    {
        return $this->getDatabase()->table('page')
            ->insert([
                'project_id' => $data->project_id,
                'title' => $data->title,
            ]);
    }


    public function deleteAllPages($project_id)
    {
        return $this->getDatabase()->table('page')
            ->where('project_id',$project_id)
            ->delete();
    }

}
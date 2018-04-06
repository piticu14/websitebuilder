<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 7.3.2018
 * Time: 11:43
 */

namespace App\Model;

use Nette;

/* TODO: make temporary data title,description,keywords (new JSON column "temporary_data" */
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
                'title' => $data->title
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

    public function get($id)
    {
        return $this->getDatabase()->table('page')->get($id);
    }

    public function update($data, $id)
    {
        return $this->getDatabase()->table('page')
            ->where('id',$id)
            ->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'keywords' => $data['keywords']
            ]);
    }

}
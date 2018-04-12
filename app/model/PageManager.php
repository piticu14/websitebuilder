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
    private function getAll($project_id,$publish)
    {
        return $this->getDatabase()->table('page')
            ->where('project_id',$project_id)
            ->where('publish',$publish)
            ->fetchAll();
    }

    public function add($data)
    {
        $temp_page = $this->getDatabase()->table('page')
            ->insert([
                'project_id' => $data->project_id,
                'title' => $data->title,
                'keywords' => isset($data->keywords) ? $data->keywords : '',
                'description' => isset($data->description) ? $data->description : '',
                'publish' => 0
            ]);

        $publish_page = $this->getDatabase()->table('page')
            ->insert([
                'project_id' => $data->project_id,
                'title' => $data->title,
                'keywords' => isset($data->keywords) ? $data->keywords : '',
                'description' => isset($data->description) ? $data->description : '',
                'publish' => 1
            ]);

        return $this->getDatabase()->table('page_relationships')
            ->insert([
                'temp_id' => $temp_page->id,
                'publish_id' => $publish_page->id
            ]);
    }


    public function deleteAll($project_id)
    {

        $pages = $this->getDatabase()->table('page')
            ->where('project_id',$project_id)->fetchAll();

        foreach ($pages as $page) {
            $this->getDatabase()->table('page_relationships')
                ->where('temp_id',$page->id)
                ->delete();
        }

        return $this->getDatabase()->table('page')
            ->where('project_id',$project_id)
            ->delete();
    }

    public function first($project_id)
    {
        $pages = $this->getDatabase()->table('page')
                ->where('project_id',$project_id)->fetchAll();
        $page_temp_nav = null;

        foreach($pages as $page){
            $page_temp_nav = $this->getDatabase()->table('nav')
                ->where('page_id',$page->id)
                ->where('sort_order',0)
                ->where('publish',0)
                ->fetch();
            if($page_temp_nav) {
                break;
            }
        }
        $page_relationship = $this->getDatabase()->table('page_relationships')
            ->where('temp_id',$page_temp_nav->page_id)
            ->fetch();
        return array(
            'temp_id' => $page_relationship->temp_id,
            'publish_id' => $page_relationship->publish_id);
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

    public function publish($project_id)
    {
        $temp_pages = $this->getAll($project_id,0);

        foreach($temp_pages as $temp_page) {
            $page_relationship = $this->getDatabase()->table('page_relationships')
                ->where('temp_id',$temp_page->id)
                ->fetch();
            bdump($page_relationship);

            $this->update($temp_page,$page_relationship->publish_id);
        }
    }

}
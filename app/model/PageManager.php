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
    private static $table = 'page';

    private function getAll($project_id,$publish)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('project_id',$project_id)
            ->where('publish',$publish)
            ->where('deleted_at',NULL)
            ->fetchAll();
    }

    public function add($data)
    {
        $temp_page = $this->getDatabase()->table(self::$table)
            ->insert([
                'project_id' => $data->project_id,
                'title' => $data->title,
                'url' => $data->page_url,
                'keywords' => isset($data->keywords) ? $data->keywords : '',
                'description' => isset($data->description) ? $data->description : '',
                'publish' => 0
            ]);

        $publish_page = $this->getDatabase()->table(self::$table)
            ->insert([
                'project_id' => $data->project_id,
                'title' => $data->title,
                'url' => $data->page_url,
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


    /*
    public function deleteAll($project_id)
    {

        $pages = $this->getDatabase()->table(self::$table)
            ->where('project_id',$project_id)->fetchAll();

        foreach ($pages as $page) {
            $this->getDatabase()->table('page_relationships')
                ->where('temp_id',$page->id)
                ->delete();
        }

        return $this->getDatabase()->table(self::$table)
            ->where('project_id',$project_id)
            ->delete();
    }
    */

    public function first($project_id)
    {
        $pages = $this->getDatabase()->table(self::$table)
                ->where('project_id',$project_id)
                ->where('deleted_at',NULL)
                ->fetchAll();
        $page_temp_nav = null;
        foreach($pages as $page){
            $page_temp_nav = $this->getDatabase()->table('nav')
                ->select('nav.*,page.url')
                ->where('page.id',$page->id)
                ->where('nav.sort_order',0)
                ->where('nav.publish',0)
                ->fetch();

            if($page_temp_nav) {
                return $page_temp_nav;
            }
        }
        /*
        $page_relationship = $this->getDatabase()->table('page_relationships')
            ->where('temp_id',$page_temp_nav->page_id)
            ->fetch();
        return array(
            'temp_id' => $page_relationship->temp_id,
            'publish_id' => $page_relationship->publish_id);
        */
    }

    public function get($id)
    {
        return $this->getDatabase()->table(self::$table)->get($id);
    }

    public function getByUrl($project_id, $url, $publish)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('project_id', $project_id)
            ->where('url', $url)
            ->where('publish', $publish)->fetch();
    }


    /** TODO: Update only if change exists */
    public function update($data, $id)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('id',$id)
            ->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'keywords' => $data['keywords'],
                'url' => $data['url'],
                'updated_at' => date("Y-m-d H:i:s")
            ]);
    }

    public function publish($project_id)
    {
        $temp_pages = $this->getDatabase()->table(self::$table)
            ->where('project_id',$project_id)
            ->where('publish',0)
            ->fetchAll();


        foreach($temp_pages as $temp_page) {
            $page_relationship = $this->getDatabase()->table('page_relationships')
                ->where('temp_id',$temp_page->id)
                ->fetch();

            if($temp_page->deleted_at === NULL){
                $this->update($temp_page,$page_relationship->publish_id);

            }else{
                $this->delete($page_relationship->publish_id);
            }
        }
    }

    public function delete($id)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('id',$id)
            ->update([
                'deleted_at' => date("Y-m-d H:i:s")
            ]);
    }
}
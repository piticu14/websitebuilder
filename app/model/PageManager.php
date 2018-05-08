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

    public function add($data)
    {
        $temp_page = $this->getDatabase()->table(self::$table)
            ->insert([
                'project_id' => $data->project_id,
                'title' => $data->title,
                'url' => $data->url,
                'keywords' => isset($data->keywords) ? $data->keywords : '',
                'description' => isset($data->description) ? $data->description : '',
                'publish' => 0
            ]);

        $publish_page = $this->getDatabase()->table(self::$table)
            ->insert([
                'project_id' => $data->project_id,
                'title' => $data->title,
                'url' => $data->url,
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


    public function first($project_id,$publish)
    {
        $first_page = $this->getDatabase()->table(self::$table)
                ->where('project_id',$project_id)
                ->where('publish',$publish)
                ->where('deleted_at',NULL)
                ->limit(1)
                ->fetch();
            return $this->getDatabase()->table('nav')
                ->select('nav.*,page.url')
                ->where('page.id',$first_page->id)
                ->where('nav.sort_order',0)
                ->where('nav.publish',$publish)
                ->fetch();

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
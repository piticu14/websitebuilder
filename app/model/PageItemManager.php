<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 4.4.2018
 * Time: 10:28
 */

namespace App\Model;

use Nette\Utils\Json;


class PageItemManager extends BaseManager
{
    private static $table = 'page_item';

    public function add($page_id, $data, $publish)
    {
        $additional = array();
        if(isset($data['photogallery_ids'])){

            $additional['photogallery_ids'] = $data['photogallery_ids'];
        }

        return $this->getDatabase()->table(self::$table)
            ->insert([
                'page_id' => $page_id,
                'content' => $data['content'],
                'order_on_page' => $data['order_on_page'],
                'publish' => $publish,
                'additional' => empty(array_filter($additional)) ? '' : JSON::encode($additional,Json::PRETTY)
            ]);
    }

    public function delete($id)
    {
        $relationship = $this->getDatabase()->table('page_item_relationships')
            ->where('temp_id',$id)->fetch();

        if($relationship){
            $this->getDatabase()->table(self::$table)
                ->where('id',$id)
                ->update([
                    'deleted_at' => date("Y-m-d H:i:s")
                ]);
            $this->getDatabase()->table(self::$table)
                ->where('id',$relationship->publish_id)
                ->update([
                    'deleted_at' => date("Y-m-d H:i:s")
                ]);
        }


    }
    /*
    public function deleteAll($project_id)
    {
        $pages = $this->getDatabase()->table('page')
            ->where('project_id',$project_id)->fetchAll();

        foreach ($pages as $page) {
            $page_items = $this->getDatabase()->table(self::$table)
                ->where('page_id',$page->id)->fetchAll();
            foreach ($page_items as  $page_item) {
                $this->getDatabase()->table('page_item_relationships')
                    ->where('temp_id',$page_item->id)->delete();

                    $this->getDatabase()->table(self::$table)
                        ->where('page_id',$page_item->id);
            }
        }
    }
    */
    public function getAll($page_id,$publish)
    {
        return $this->getDatabase()->table(self::$table)
            ->select('id,content,order_on_page,additional,page_id')
            ->where('page_id',$page_id)
            ->where('publish',$publish)
            ->where('deleted_at',null)
            ->order('order_on_page','ASC')
            ->fetchAll();
    }

    public function update($id,$data,$publish)
    {
        $additional = array();
        if(isset($data['photogallery_ids'])){

            $additional['photogallery_ids'] = $data['photogallery_ids'];
        }

        $this->getDatabase()->table(self::$table)
            ->where('id',$id)
            ->where('publish',$publish)
            ->update([
                'content' => $data['content'],
                'order_on_page' => $data['order_on_page'],
                'additional' => empty(array_filter($additional)) ? '' : JSON::encode($additional,Json::PRETTY),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
    }

    public function publish($project_id)
    {
        $pages = $this->getDatabase()->table('page')
                ->where('project_id',$project_id)->fetchAll();

        foreach ($pages as $page) {
            $page_items = $this->getAll($page->id,0);
            foreach ($page_items as  $page_item) {
                bdump($page_item);

                $item_relationship = $this->getDatabase()->table('page_item_relationships')
                    ->where('temp_id',$page_item->id);

                if($item_relationship->count()) {
                    $row = $item_relationship->fetch();
                    $this->getDatabase()->table(self::$table)
                        ->where('id',$row->publish_id)
                        ->update([
                            'content' => $page_item->content,
                            'order_on_page' => $page_item->order_on_page,
                            'additional' => $page_item->additional,
                            'updated_at' => date("Y-m-d H:i:s")
                        ]);
                } else {
                    $page_relationship = $this->getDatabase()->table('page_relationships')
                        ->where('temp_id',$page_item->page_id)
                        ->fetch();
                    $published_page_item = $this->add($page_relationship->publish_id,$page_item,1);
                    $this->getDatabase()->table('page_item_relationships')->insert([
                        'temp_id' => $page_item->id,
                        'publish_id' => $published_page_item->id
                    ]);

                }
            }
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 4.4.2018
 * Time: 10:28
 */

namespace App\Model;


class PageItemManager extends BaseManager
{
    private static $table = 'page_item';

    public function add($page_id, $data, $publish)
    {
        return $this->getDatabase()->table(self::$table)
            ->insert([
                'page_id' => $page_id,
                'content' => $data['content'],
                'order_on_page' => $data['order_on_page'],
                'publish' => $publish
            ]);
    }

    public function delete($id)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('id',$id)
            ->delete();
    }
    public function deleteAll($page_id)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('page_id',$page_id)
            ->delete();
    }

    public function getAll($page_id,$publish)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('page_id',$page_id)
            ->where('publish',$publish)
            ->order('order_on_page','ASC')
            ->fetchAll();
    }

    public function update($id,$data,$publish)
    {
        $this->getDatabase()->table(self::$table)
            ->where('id',$id)
            ->where('publish',$publish)
            ->update([
                'content' => $data['content'],
                'order_on_page' => $data['order_on_page']
            ]);
    }

}
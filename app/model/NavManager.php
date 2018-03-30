<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 7.3.2018
 * Time: 11:48
 */

namespace App\Model;

use Nette;
use Nette\Utils\Strings;


class NavManager extends BaseManager
{
    public function add($data, $publish)
    {
        return $this->getDatabase()->table('nav')
            ->insert([
                'page_id' => $data->page_id,
                'title' => $data->title,
                'url' => Strings::lower($data->title),
                'sort_order' => $data->sort_order,
                'publish' => $publish
            ]);

    }

    public function delete($project_id)
    {
        $pages = $this->getDatabase()->table('page')->where('project_id',$project_id);
        return $this->getDatabase()->table('nav')->where('page_id',$pages)->delete();
    }

    public function get($project_id, $publish)
    {

        return $this->getDatabase()->table('nav')
            ->select('page.id AS page_id,page.title AS page_title,page.description,page.keywords,nav.*')
            ->where('page.project_id', $project_id)
            ->where('nav.publish',$publish)
            ->fetchAll();
    }

    public function update($data, $page_id, $publish) {

        $nav_item = $this->getDatabase()->table('nav')
            ->where('page_id',$page_id)
            ->where('publish',$publish);
        if($nav_item->count()) {
            return $nav_item->update(array(
                'title' => $data['text'],
                'sort_order' => $data['sort_order'],
                'active' => $data['active']
            ));
        } else {
            return $this->getDatabase()->table('nav')
                ->insert([
                    'title' => $data['text'],
                    'sort_order' => $data['sort_order'],
                    'active' => $data['active'],
                    'new' => 1
                ]);
        }
    }

}
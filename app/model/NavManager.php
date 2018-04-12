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
    private static $table = 'nav';

    public function add($data, $publish)
    {
        return $this->getDatabase()->table(self::$table)
            ->insert([
                'page_id' => $data->page_id,
                'title' => $data->title,
                'url' => Strings::lower($data->title),
                'sort_order' => $data->sort_order,
                'new' => isset($data->new) ? 1 : 0,
                'publish' => $publish
            ]);

    }

    public function delete($project_id)
    {
        $pages = $this->getDatabase()->table('page')->where('project_id',$project_id);
        return $this->getDatabase()->table(self::$table)->where('page_id',$pages)->delete();
    }

    public function get($project_id, $publish)
    {

        return $this->getDatabase()->table(self::$table)
            ->select('page.id AS page_id,page.title AS page_title,page.description,page.keywords,nav.*')
            ->where('page.project_id', $project_id)
            ->where('nav.publish',$publish)
            ->order('sort_order','ASC')
            ->fetchAll();
    }


    public function update($data, $page_id, $publish) {

        return $this->getDatabase()->table(self::$table)
            ->where('page_id',$page_id)
            ->where('publish',$publish)
            ->update(array(
                'title' => $data['text'],
                'sort_order' => $data['sort_order'],
                'active' => $data['active']
            ));

    }

    public function publish($project_id)
    {
     $temp_navs = $this->get($project_id,0);


        foreach($temp_navs as $temp_nav) {
            $page_relationship = $this->getDatabase()->table('page_relationships')
                ->where('temp_id', $temp_nav->page_id)
                ->fetch();
            $publish_nav = $this->getDatabase()->table(self::$table)
                ->where('page_id',$page_relationship->publish_id)
                ->where('publish',1);

            if($publish_nav->count()){
                bdump('Found');
            } else {
                bdump('Not found');
            }

            if($publish_nav->count()){
                $publish_nav->update([
                    'url' => $temp_nav->url,
                    'sort_order' => $temp_nav->sort_order,
                    'active' => $temp_nav->active,
                    'title' => $temp_nav->title
                ]);
            } else {
                $new_publish_nav = $temp_nav->toArray();
                $new_publish_nav['page_id'] = $page_relationship->publish_id;

                $this->add(Nette\Utils\ArrayHash::from($new_publish_nav),1);
            }

        }
    }

}
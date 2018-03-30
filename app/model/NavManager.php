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
                'project_id' => $data->project_id,
                'page_id' => $data->page_id,
                'title' => $data->title,
                'url' => Strings::lower($data->title),
                'sort_order' => $data->sort_order,
                'publish' => $publish
            ]);

    }

    public function delete($project_id)
    {
        return $this->getDatabase()->table('nav')
            ->where('project_id', $project_id)
            ->delete();
    }

    public function get($project_id, $publish)
    {

        return $this->getDatabase()->table('nav')
            ->select('page.title AS page_title,page.description,page.keywords,nav.id,nav.project_id,nav.title,nav.url,nav.active,nav.sort_order')
            ->where('nav.project_id', $project_id)
            ->where('publish',$publish)
            ->fetchAll();
    }

    public function update($data, $poject_id, $publish) {
        bdump($data);
    }

}
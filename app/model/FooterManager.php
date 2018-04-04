<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 3.4.2018
 * Time: 19:19
 */

namespace App\Model;

use Nette;
use Nette\Utils\Json;


class FooterManager extends BaseManager
{
    private static $table = 'footer';

    public function add($project_id, $data, $publish)
    {
        return $this->getDatabase()->table(self::$table)
            ->insert([
                'project_id' => $project_id,
                'content' => $data['content'],
                'publish' => $publish,
                'social_media' => $data['social_media']
            ]);
    }

    public function get($project_id)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('project_id',$project_id)
            ->fetch();
    }

    public function delete($project_id)
    {
        return $this->getDatabase()->table(self::$table)->where('project_id', $project_id)->delete();
    }

    public function update($project_id, $data, $publish)
    {
        bdump($data);
        return $this->getDatabase()->table(self::$table)
            ->where('project_id',$project_id)
            ->where('publish',$publish)
            ->update([
                'content' => $data['content'],
                'social_media' =>  Json::encode($data['social_media'], Json::PRETTY)
            ]);
    }

}
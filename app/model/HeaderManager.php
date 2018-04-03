<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 1.4.2018
 * Time: 20:44
 */

namespace App\Model;

use Nette;
use Nette\Utils\Json;


class HeaderManager extends BaseManager
{
    private static $table = 'header';

    public function add($data, $project_id, $publish)
    {
        return $this->getDatabase()->table(self::$table)
            ->insert([
                'project_id' => $project_id,
                'title' => $data->title,
                'subtitle' => $data->subtitle,
                'logo' => $data->logo,
                'publish' => $publish
            ]);

    }

    public function delete($project_id)
    {
        return $this->getDatabase()->table(self::$table)->where('project_id', $project_id)->delete();
    }

    public function get($project_id, $publish)
    {

        return $this->getDatabase()->table(self::$table)
            ->where('project_id', $project_id)
            ->where('publish', $publish)
            ->fetch();
    }

    public function update($data, $project_id, $publish)
    {

        return $this->getDatabase()->table(self::$table)
            ->where('project_id', $project_id)
            ->where('publish', $publish)
            ->update([
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'logo' => Json::encode($data['logo'], Json::PRETTY)
            ]);
    }
}
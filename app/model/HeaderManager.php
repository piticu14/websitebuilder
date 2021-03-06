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

    /*
    public function delete($project_id)
    {
        return $this->getDatabase()->table(self::$table)->where('project_id', $project_id)->delete();
    }

    */
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
                'logo' => Json::encode($data['logo'], Json::PRETTY),
                'background' => $data['background'],
                'nav_color' => $data['nav_color'],
                'nav_color_hover' => $data['nav_color_hover'],
                'updated_at' => date("Y-m-d H:i:s")
            ]);
    }

    public function publish($project_id)
    {
        $temp_data = $this->get($project_id,0);
        return $this->getDatabase()->table(self::$table)
            ->where('project_id',$project_id)
            ->where('publish',1)
            ->update([
                'title' => $temp_data->title,
                'subtitle' => $temp_data->subtitle,
                'logo' => $temp_data->logo,
                'background' => $temp_data->background,
                'nav_color' => $temp_data->nav_color,
                'nav_color_hover' => $temp_data->nav_color_hover
            ]);

    }
}
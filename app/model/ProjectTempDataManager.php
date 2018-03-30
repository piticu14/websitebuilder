<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 31.3.2018
 * Time: 0:25
 */

namespace App\Model;

use Nette;


class ProjectTempDataManager extends BaseManager
{
    private static $table = 'project_temp_data';


    public function add($data, $project_id)
    {
        return $this->getDatabase()->table(self::$table)
            ->insert([
                'project_id' => $project_id,
                'title' => $data->title,
                'subtitle' => $data->subtitle,
                'logo' => $data->logo
            ]);
    }

    public function delete($project_id)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('project_id', $project_id)
            ->delete();
    }

    public function get($project_id)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('project_id',$project_id)
            ->fetch();
    }

    public function update($data,$project_id)
    {
        return $this->getDatabase()->table(self::$table)
            ->where('project_id',$project_id)
            ->update([
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'logo' => $data['logo']
            ]);
    }

}
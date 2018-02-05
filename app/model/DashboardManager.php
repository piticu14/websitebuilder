<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 5.2.2018
 * Time: 21:22
 */

namespace App\Model;

use Nette;


class DashboardManager extends BaseManager
{

    public function getTemplates(){
        return $this->getDatabase()->table('templates')->fetchAll();
    }
}
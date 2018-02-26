<?php
/**
 * Created by PhpStorm.
 * User: Piticu
 * Date: 26.2.2018
 * Time: 22:54
 */

namespace App\Model;

use Nette;
use Nette\Utils\Strings;


class SiteManager extends BaseManager
{
    public function addSite($data)
    {
        $data['user_id'] = $this->getUser()->id;
        $data['subdomain'] = Strings::trim($data['subdomain']);
        return $this->getDatabase()->table('site')->insert($data);
    }

    public function subdomainDuplicate($subdomain)
    {
        return $this->getDatabase()->table('site')
            ->where('subdomain',$subdomain)
            ->count();
    }

    public function getLastInsertedId()
    {
       return $this->getDatabase()->table('site')
           ->order('id DESC')
           ->limit(1)
           ->fetch()->id;
    }
}
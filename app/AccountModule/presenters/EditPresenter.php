<?php
namespace App\Presenters;

use Nette;
use App\Model\Users;

class EditPresenter extends BasePresenter
{
    /** @var Users */
    private $users;


    /**
     * AccountPresenter constructor.
     * @param Users $users
     */

    public function __construct(Users $users)
    {
        $this->users = $users;
    }
}

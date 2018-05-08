<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('activate/email[/<token>]', 'Settings:emailActivation');
		$router[] = new Route('activate/account[/<token>]','Account:Activation');
		$router[] = new Route('resetPassword[/<token>]','Account:reset');
		/*
		$router[] = new Route('<subdomain>.quickweb.fun/edit</page_url]','Project:edit');
		$router[] = new Route('<subdomain>.quickweb.fun[/<page_url]','Project:show');
		*/
		$router[] = new Route('<presenter>/<action>[/<subdomain>][/<page_url>]', 'Homepage:default');

		return $router;

	}

}

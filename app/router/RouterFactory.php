<?php

namespace App;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

/**
 * Routovací továrnička.
 * Řídí routování v celé aplikaci.
 * @package App
 */
class RouterFactory
{
	/**
	 * Vytváří router pro aplikaci.
	 * @return RouteList výsledný router pro aplikaci
	 */
	public static function createRouter()
	{
		$router = new RouteList();
        $router[] = new Route('kontakt/', 'Core:Contact:default');
        $router[] = new Route('administrace/', 'Core:Administration:default');
        $router[] = new Route('prihlasit', 'Core:Session:signIn');
        $router[] = new Route('odhlasit', 'Core:Session:signOut');
        $router[] = new Route('registrovat', 'Core:Session:signUp');
        $router[] = new Route('seznam-uzivatelu/', 'Core:User:list');
        $router[] = new Route('profil/[<action>/][<username>]', array(
            'presenter' => 'Core:User',
            'action' => array(
                Route::VALUE => 'default',
                Route::FILTER_TABLE => array(
                    //řetězec v URL=> akce presenteru
                    'editor' => 'editor',
                    'odstranit' => 'remove'
                ),
                Route::FILTER_STRICT => true
            ),
            'username' => null,
        ));
        $router[] = new Route('profil/[<username>]', 'Core:User:default');
        $router[] = new Route('[<action>/][<url>]', array(
            'presenter' => 'Core:Article',
            'action' => array(
                Route::VALUE => 'default',
                Route::FILTER_TABLE => array(
                    //řetězec v URL=> akce presenteru
                    'seznam-clanku' => 'list',
                    'editor' => 'editor',
                    'odstranit' => 'remove'
                ),
                Route::FILTER_STRICT => true
            ),
            'url' => null,
        ));
		$router[] = new Route('[<url>]', 'Core:Article:default');
		return $router;
	}
}

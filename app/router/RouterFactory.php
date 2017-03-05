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
        $router[] = new Route('administrace', 'Admin:Web:default');
        $router[] = new Route('kontakt', 'Core:Contact:default');
        $router[] = new Route('prihlasit', 'Core:Session:signIn');
        $router[] = new Route('odhlasit', 'Core:Session:signOut');
        $router[] = new Route('registrovat', 'Core:Session:signUp');
        $router[] = new Route('seznam-uzivatelu', 'Core:User:list');
        $router[] = new Route('clanky', 'Core:Article:list');
        $router[] = new Route('novinky', 'Core:News:pagedList');
        $router[] = new Route('novinky/seznam', 'Core:News:list');
        $router[] = new Route('administrace/uzivatele', 'Admin:User:management');

        $router[] = new Route('administrace/zmen-roli/<role>/<username>', 'Admin:User:changeRole');
        $router[] = new Route('administrace/clanky/<action>[/<url>]', array(
            'presenter' => 'Admin:Article',
            'action' => array(
                Route::FILTER_TABLE => array(
                    'editor' => 'editor',
                    'odstranit' => 'remove'
                ),
                Route::FILTER_STRICT => true
            )
        ));
        $router[] = new Route('administrace/novinky/<action>/[<url>]', array(
            'presenter' => 'Admin:News',
            'action' => array(
                Route::FILTER_TABLE => array(
                    //řetězec v URL=> akce presenteru
                    'editor' => 'editor',
                    'odstranit' => 'remove'
                ),
                Route::FILTER_STRICT => true
            ),
            'url' => null
        ));
        $router[] = new Route('instalace/[<action>]', array(
            'presenter' => 'Install:Install',
            'action' => array(
                Route::VALUE => 'projectProperties',
                Route::FILTER_TABLE => array(
                    'vlastnosti' => 'projectProperties',
                    'admin' => 'adminAccount'
                ),
                Route::FILTER_STRICT => true
            )
        ));
        $router[] = new Route('profil/[<action>/][<username>]', array(
            'presenter' => 'Core:User',
            'action' => array(
                Route::VALUE => 'default',
                Route::FILTER_TABLE => array(
                    //řetězec v URL=> akce presenteru
                    'editor' => 'editor',
                    'odstranit' => 'remove',
                    'zmenit-heslo' => 'changePassword'
                ),
                Route::FILTER_STRICT => true
            ),
            'username' => null,
        ));
        $router[] = new Route('profil/[<username>]', 'Core:User:default');
        $router[] = new Route('novinky[/<page [0-9]+>]', 'Core:News:pagedList');
        $router[] = new Route('novinky/[<url>]', 'Core:News:default');
        $router[] = new Route('[<url>]', 'Core:Article:default');
        return $router;
    }
}

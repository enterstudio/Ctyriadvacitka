<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

use Nette\Database\Context;
use Nette\Object;
use Nette\Security\User;

/**
 * Description of BaseManager
 * Základní třída modelu pro všechny modely aplikace
 * @package App\Model
 * @author matyas
 */
abstract class BaseManager extends Object{
    
    /** @var Content Instance třídy pro práci s databází */
    protected $database;
    /**
     * @var User instance třídy pro authentikaci uživatelů
     */
    protected $user;
    
    
    /**
     * 
     * @param Context $database automaticky injektovaná třída pro práci s databází
     */
    public function __construct(Context $database, User $user) {
        $this->database = $database;
        $this->user = $user;
    }
}

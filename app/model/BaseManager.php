<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model;

use Nette\Database\Context;
use Nette\Object;

/**
 * Description of BaseManager
 * Základní třída modelu pro všechny modely aplikace
 * @package App\Model
 * @author matyas
 */
abstract class BaseManager extends Object
{

    /** @var Content Instance of class to work with DB */
    protected $database;
    /** @var DatabaseHelper Instance of class to help with DB */
    protected $databaseHelper;

    /**
     * @param Context $database automatically injected class to work with DB
     * @param DatabaseHelper $databaseHelper automatically injected class to help with DB
     */
    public function __construct(Context $database, DatabaseHelper $databaseHelper)
    {
        $this->database = $database;
        $this->databaseHelper = $databaseHelper;
    }

    /**
     * @return DatabaseHelper
     */
    public function getDatabaseHelper(): DatabaseHelper
    {
        return $this->databaseHelper;
    }
}

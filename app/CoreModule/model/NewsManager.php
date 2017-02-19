<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 21.1.17
 * Time: 17:34
 */

namespace App\CoreModule\Model;


use App\Model\DatabaseHelper;
use Nette\Database\Context;

/**
 * Service to work with News
 * Class NewsManager
 * @package App\CoreModule\Model
 */
class NewsManager extends EntityManager
{
    /**
     * NewsManager constructor.
     * @param Context $database
     * @param DatabaseHelper $databaseHelper
     */
    public function __construct(Context $database, DatabaseHelper $databaseHelper)
    {
        parent::__construct($database, $databaseHelper, 'news');
    }
}
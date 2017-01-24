<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 21.1.17
 * Time: 17:34
 */

namespace App\CoreModule\Model;


use Nette\Database\Context;
use Nette\Security\User;

/**
 * Service to work with News
 * Class NewsManager
 * @package App\CoreModule\Model
 */
class NewsManager extends ResourceManager{
    public function __construct(Context $database, User $user)
    {
        parent::__construct($database, $user);
        $this->tableName = 'news';
        $this->columnID = 'news_id';
    }
}
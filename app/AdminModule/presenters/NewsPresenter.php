<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 5.3.17
 * Time: 12:35
 */

namespace App\AdminModule\Presenters;


/**
 * Class NewsPresenter
 * @package App\AdminModule\Presenters
 */
class NewsPresenter extends ArticlePresenter
{
    public function startup()
    {
        parent::startup();
        $this->entityManager = $this->newsManager;
        $this->presenter = ':News:';
    }
}
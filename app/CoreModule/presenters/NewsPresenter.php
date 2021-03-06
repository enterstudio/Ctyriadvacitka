<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 21.1.17
 * Time: 20:49
 */

namespace App\CoreModule\Presenters;


/**
 * Processes rendering news
 * Class NewsPresenter
 * @package App\CoreModule\Presenters
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

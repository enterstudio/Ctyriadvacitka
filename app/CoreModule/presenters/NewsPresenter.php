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
        $this->resourceManager = $this->newsManager;
        $this->presenter = ':Core:News:';
    }

    /**
     * Renders list of news on pages. There are 4 news in ine page
     * @param int $page page of list
     */
    public function renderPagedList(int $page = 1){
        $offset = ($page - 1) * 4;
        $this->template->articles = $this->resourceManager->getArticles(4, $offset);
    }
}

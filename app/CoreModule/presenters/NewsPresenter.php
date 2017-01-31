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

    public function renderPagedList(int $page = 1){
        $news = $this->resourceManager->getArticles();
        $selectedNews = $news;
        $this->template->articles = $selectedNews;
    }

}
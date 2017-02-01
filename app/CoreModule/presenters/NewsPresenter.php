<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 21.1.17
 * Time: 20:49
 */

namespace App\CoreModule\Presenters;
use App\CoreModule\Model\ResourceManager;


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
        $news = $this->resourceManager->getArticles()->where(ResourceManager::COLUMN_REQUESTABLE, 1);
        $offset = ($page - 1) * 4;
        $pages = ceil($news->count()/4);

        if ($page > $pages){
            $this->flashMessage('Tolik stránek tu nemáme.', 'warning');
            $this->redirect(':Core:News:pagedList');
        }

        $this->template->articles = $news->limit(4, $offset);
        $this->template->activePage = $page;
        $this->template->pages = $pages;
    }
}

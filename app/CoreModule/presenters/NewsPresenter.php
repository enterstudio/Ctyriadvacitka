<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 21.1.17
 * Time: 20:49
 */

namespace App\CoreModule\Presenters;

use app\CoreModule\model\TableRow;
use Nette\Utils\Strings;


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

    /**
     * Renders list of news on pages. There are 4 news in ine page
     * @param int $page page of list
     * @throws \Nette\Application\AbortException
     */
    public function renderPagedList(int $page = 1){
        $news = $this->entityManager->getEntities()->where('requestable', 1);
        $offset = ($page - 1) * 4;
        $pages = ceil($news->count()/4);

        if ($page > $pages){
            $this->flashMessage('Tolik stránek tu nemáme.', 'warning');
            $this->redirect(':Core:News:pagedList');
        }

        $news = $news->limit(4, $offset);
        $tableRows = [];

        foreach ($news as $new){
            $tableRow = new TableRow($new);
            $tableRow->setContent(Strings::truncate($tableRow->getContent(), 80));
            $tableRows[] = $tableRow;
        }

        $this->template->articles = $tableRows;
        $this->template->activePage = $page;
        $this->template->pages = $pages;
    }
}

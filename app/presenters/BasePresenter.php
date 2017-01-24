<?php

namespace App\Presenters;

use App\CoreModule\Model\ArticleManager;
use App\CoreModule\Model\AuthenticatorManager;
use App\CoreModule\Model\AuthorizatorManager;
use App\CoreModule\Model\NewsManager;
use App\CoreModule\Model\ResourceManager;
use App\CoreModule\Model\UserManager;
use Nette\Application\UI\Presenter;
use Nette\Security\User;


/**
 * Base presenter for all application presenters.
 * @package App\Presenters
 */
abstract class BasePresenter extends Presenter{

    /** @var  User instance of User */
    protected $user;
    /** @var UserManager instance of UserManager */
    protected $userManager;
    /** @var  ArticleManager instance of ArticleManager */
    protected $articleManager;
    /** @var  NewsManager instance of NewsManager */
    protected $newsManager;
    /** @var  ResourceManager instance of ResourceManager */
    protected $resourceManager;

    /**
     * Gets instances of Services from DI
     * @param UserManager $userManager instance of UserManager
     * @param ArticleManager $articleManager instance of ArticleManager
     * @param NewsManager $newsManager instance of ArticleManager
     */
    public function injectServices(UserManager $userManager, ArticleManager $articleManager, NewsManager $newsManager){
        $this->userManager = $userManager;
        $this->articleManager = $articleManager;
        $this->newsManager = $newsManager;
    }

    public function startup()
    {
        parent::startup();
        $this->user = $this->getUser();
        $this->user->setAuthenticator(new AuthenticatorManager($this->userManager->getDatabase()));
        $this->user->setAuthorizator(new AuthorizatorManager());
    }

    public function beforeRender()
    {
        parent::beforeRender();
        $this->template->isLoggedIn = $this->user->isLoggedIn();
        $this->template->loggedUser = $this->user->getIdentity();
    }

}

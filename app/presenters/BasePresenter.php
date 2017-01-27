<?php

namespace App\Presenters;

use App\CoreModule\Model\ArticleManager;
use App\CoreModule\Model\AuthenticatorManager;
use App\CoreModule\Model\AuthorizatorManager;
use App\CoreModule\Model\NewsManager;
use App\CoreModule\Model\ResourceManager;
use App\CoreModule\Model\UserManager;
use Instante\Bootstrap3Renderer\BootstrapFormFactory;
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
    /** @var  BootstrapFormFactory FormFactory which supports Bootstrap 3 */
    protected $formFactory;

    /**
     * Gets instances of Services from DI
     * @param UserManager $userManager instance of UserManager
     * @param ArticleManager $articleManager instance of ArticleManager
     * @param NewsManager $newsManager instance of ArticleManager
     * @param BootstrapFormFactory $formFactory instance of FormFactory
     */
    public function injectServices(UserManager $userManager, ArticleManager $articleManager, NewsManager $newsManager, BootstrapFormFactory $formFactory){
        $this->userManager = $userManager;
        $this->articleManager = $articleManager;
        $this->newsManager = $newsManager;
        $this->formFactory = $formFactory;
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
        if ($this->template->isLoggedIn = $this->user->isLoggedIn()){
            $this->template->loggedUser = $this->user->getIdentity();
        }
        $this->template->isUserAdmin = $this->user->isInRole('administrator');
    }

}

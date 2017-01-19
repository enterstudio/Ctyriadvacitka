<?php

namespace App\Presenters;

use App\CoreModule\Model\ArticleManager;
use App\CoreModule\Model\AuthenticatorManager;
use App\CoreModule\Model\AuthorizatorManager;
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

    /**
     * Gets instances of Services from DI
     * @param UserManager $userManager instance of UserManager
     * @param ArticleManager $articleManager instance of ArticleManager
     */
    public function injectServices(UserManager $userManager, ArticleManager $articleManager){
        $this->userManager = $userManager;
        $this->articleManager = $articleManager;
    }

    public function startup()
    {
        parent::startup();
        $this->user = $this->getUser();
        $this->user->setAuthenticator(new AuthenticatorManager($this->userManager->getDatabase()));
        $this->user->setAuthorizator(new AuthorizatorManager());
    }

}

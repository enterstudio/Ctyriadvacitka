<?php

namespace App\Presenters;

use App\AdminModule\Model\ImageManager;
use App\CoreModule\Model\ArticleManager;
use App\CoreModule\Model\AuthenticatorManager;
use App\CoreModule\Model\AuthorizatorManager;
use App\CoreModule\Model\EntityManager;
use App\CoreModule\Model\NewsManager;
use App\CoreModule\Model\UserManager;
use App\Model\ProjectManager;
use Instante\Bootstrap3Renderer\BootstrapFormFactory;
use Nette\Application\UI\Presenter;
use Nette\Security\User;


/**
 * Base presenter for all application presenters.
 * @package App\Presenters
 */
abstract class BasePresenter extends Presenter
{

    /** @var  User instance of User */
    protected $user;
    /** @var UserManager instance of UserManager */
    protected $userManager;
    /** @var  ArticleManager instance of ArticleManager */
    protected $articleManager;
    /** @var  NewsManager instance of NewsManager */
    protected $newsManager;
    /** @var  EntityManager instance of ResourceManager */
    protected $entityManager;
    /** @var  BootstrapFormFactory FormFactory which supports Bootstrap 3 */
    protected $formFactory;
    /** @var  ImageManager */
    protected $imageManager;
    /** @var  ProjectManager */
    protected $projectManager;

    /**
     * Gets instances of Services from DI
     * @param UserManager $userManager instance of UserManager
     * @param ArticleManager $articleManager instance of ArticleManager
     * @param NewsManager $newsManager instance of ArticleManager
     * @param BootstrapFormFactory $formFactory instance of FormFactory
     * @param ProjectManager $projectManager
     * @param ImageManager $imageManager
     */
    public function injectServices(
        UserManager $userManager,
        ArticleManager $articleManager,
        NewsManager $newsManager,
        BootstrapFormFactory $formFactory,
        ProjectManager $projectManager,
        ImageManager $imageManager)
    {
        $this->userManager = $userManager;
        $this->articleManager = $articleManager;
        $this->newsManager = $newsManager;
        $this->formFactory = $formFactory;
        $this->projectManager = $projectManager;
        $this->imageManager = $imageManager;
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
        if ($this->template->isLoggedIn = $this->user->isLoggedIn()) {
            $this->template->loggedUser = $this->user->getIdentity();
        }
        $this->template->isUserAdmin = $this->user->isInRole('admin');
        $this->template->isUserEditor = $this->user->isInRole('editor') || $this->user->isInRole('admin');
        if ($this->presenter !== null) {
            $this->template->presenter = $this->presenter;
        }

        $this->template->webName = $this->projectManager->getParameter('webName');
        $this->template->webDescription = $this->projectManager->getParameter('webDescription');

        //header picture
        $images = $this->imageManager->getHeaderImageNames();
        $topRandom = random_int(0, count($images) - 1);
        $this->template->headerPicture = $images[$topRandom];
    }

    public function logInRequired()
    {
        if (!$this->user->isLoggedIn()) {
            $this->flashMessage('Nejste přihlášen!', 'warning');
            $this->redirect(':Core:Session:signIn');
        }
    }

    public function editorPermissionsRequired()
    {
        if (!$this->user->isInRole('editor') && !$this->user->isInRole('admin')) {
            $this->flashMessage('Na tuto akci musíte být redaktor.', 'warning');
            $this->redirect(':Core:User:');
        }
    }

    public function adminPermissionsRequired()
    {
        if (!$this->user->isInRole('admin')) {
            $this->flashMessage('Na tuto akci musíte být administrátor.', 'warning');
            $this->redirect(':Core:User:');
        }
    }

    /**
     * Editors can only edit their articles/news and admins every article/news
     * @param string $url
     * @throws \Nette\Application\AbortException
     */
    public function articlePermissionRequired(string $url = null)
    {
        $this->editorPermissionsRequired();
        if (isset($url) && $this->entityManager->entityExists($url)) {
            $article = $this->entityManager->getEntityByUnique($url);
            if (!($this->user->isInRole('admin') ||
                ($this->user->getIdentity()->username === $article->author && $this->user->isInRole('editor')))
            ) {
                $this->flashMessage('Na tuto akci potřebujete oprávnění administrátora nebo redaktora a vlastnit tento článek.');
                $this->redirect(":Core$this->presenter", $url);
            }
        }
    }

    /**
     * @param string $code
     * @param null $destination
     * @param array|null $snippets
     * @throws \Nette\Application\AbortException
     */
    public function redirectAjax(string $code, $destination = null, array $snippets = null)
    {
        if ($this->isAjax()) {
            $this->redrawControl($snippets);
        } else {
            if ($destination === null) {
                $this->redirect($code);
            } else {
                $this->redirect($code, $destination);
            }
        }
    }
}

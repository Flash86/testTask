<?php
namespace Acme\Ecommerce\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.Ecommerce".        *
 *                                                                        *
 *                                                                        */

use Acme\Ecommerce\Components\Auth\UserAuthSession;
use Acme\Ecommerce\Helpers\ResponseHelp;
use TYPO3\Flow\Annotations;
use TYPO3\Flow\Mvc\Controller\ActionController;
use Acme\Ecommerce\Domain\Model\User;
use TYPO3\Flow\Annotations as Flow;

/**
 * Class UserController
 * @package Acme\Ecommerce\Controller
 */
class UserController extends ActionController
{
    /**
     * @Flow\Inject
     * @var \Acme\Ecommerce\Domain\Repository\UserRepository
     */
    protected $userRepository;

    /**
     * @Flow\Inject
     * @var UserAuthSession
     */
    protected $userAuthSession;

    /**
     * @var string
     */
    protected $defaultViewObjectName = 'TYPO3\Flow\Mvc\View\JsonView';

    const SALT = 'saltForPassword';

    public function indexAction()
    {
        $userId = $this->userAuthSession->getUserId();
        if($userId){
            $this->view->assign('value', ResponseHelp::getSuccesMessage(['userId' => $userId]));
        }else{
            $this->view->assign('value', ResponseHelp::getErrorMessage("You did not login"));
            $this->response->setStatus(403);
        }

    }

    /**
     * @param string $login
     * @param string $password
     */
    public function loginAction($login, $password)
    {
        $user = $this->userRepository->findOneByLogin($login);
        if (($user instanceof User) && $this->getPasswordHash($password) == $user->getPassword()) {
            $this->userAuthSession->loginUser($user);
            $this->view->assign('value', ResponseHelp::getSuccesMessage(['userId' => $user->getId()]));
        } else {
            $this->view->assign('value', ResponseHelp::getErrorMessage('User not found'));
        }
    }


    public function logoutAction()
    {
        $this->userAuthSession->logoutUser();
        $this->view->assign('value', ResponseHelp::getSuccesMessage());
    }

    /**
     * @param $password
     * @return string
     */
    private function getPasswordHash($password)
    {
        //I can use password_hash() or other methods,
        // but i don't know version of php on your server
        return md5($password . self::SALT);
    }

}
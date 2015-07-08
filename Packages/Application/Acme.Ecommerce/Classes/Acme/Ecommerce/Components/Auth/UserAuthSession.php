<?php
/**
 * Created by PhpStorm.
 * User: zemfr
 * Date: 05.07.15
 * Time: 20:59
 */

namespace Acme\Ecommerce\Components\Auth;


use Acme\Ecommerce\Domain\Model\User;
use TYPO3\Flow\Annotations as Flow;


/**
 * Class UserAuthSession
 * @package Acme\Ecommerce\Components
 * @Flow\Scope("session")
 */
class UserAuthSession implements AuthManagerInterface
{

    /**
     * @var integer
     */
    protected $userId;

    /**
     * @param User $user
     * @return void
     * @Flow\Session(autoStart = TRUE)
     */
    public function loginUser(User $user)
    {
        $this->userId = $user->getId();
    }


    public function getUserId()
    {
        return $this->userId;
    }

    public function logoutUser()
    {
        $this->userId = null;
    }

}
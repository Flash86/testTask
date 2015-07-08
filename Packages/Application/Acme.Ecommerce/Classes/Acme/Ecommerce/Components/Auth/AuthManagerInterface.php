<?php
/**
 * Created by PhpStorm.
 * User: zemfr
 * Date: 05.07.15
 * Time: 20:59
 */

namespace Acme\Ecommerce\Components\Auth;

use Acme\Ecommerce\Domain\Model\User;

interface AuthManagerInterface
{
    public function loginUser(User $user);

    public function getUserId();

    public function logoutUser();

}
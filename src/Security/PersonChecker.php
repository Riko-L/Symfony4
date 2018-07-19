<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 18/07/18
 * Time: 14:16
 */

namespace App\Security;


use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Person as AppPerson;

class PersonChecker implements UserCheckerInterface
{

    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user instanceof AppPerson){
            return;
        }

        if($user->getIsDelete()){

                throw new \Exception("utilisateur inconnu");

        }
    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}
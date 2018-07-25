<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 25/07/18
 * Time: 15:17
 */

namespace App\Security;



use App\Entity\Person;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AccountVoter extends Voter
{


    const VIEW = 'view';
    const EDIT = 'edit';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // TODO: Implement supports() method.
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof Person) {
            // the user must be logged in; if not, deny access
            return false;
        }

        $account = $subject;


        switch ($attribute) {
            case self::VIEW:
                return $this->canView($account, $user);
            case self::EDIT:
                return $this->canEdit($account, $user);
        }

        throw new \LogicException('This code should not be reached!');

    }

    private function canView(Person $account, Person $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($account, $user)) {
            return true;
        }

        // the Post object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return !$post->isPrivate();
    }

    private function canEdit(Post $account, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $account->getOwner();
    }

}
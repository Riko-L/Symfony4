<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 25/07/18
 * Time: 15:17
 */

namespace App\Security;



use App\Entity\Ads;
use App\Entity\Person;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdsVoter extends Voter
{



    const EDIT = 'edit';
    private $decisionManager;




    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }


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
        if (!$attribute === self::EDIT) {
            return false;
        }


        if (!$subject instanceof Ads) {
            return false;
        }


        return true;
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
            return false;
        }

        return $subject->getAuthor() === $token->getUser() ||
            $this->decisionManager->decide($token, array('ROLE_MODERATOR'));

    }
}
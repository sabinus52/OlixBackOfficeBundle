<?php
/**
 * Classe pour checker l'autorisation des utilsateurs
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 * @see https://symfony.com/doc/current/security/user_checkers.html
 */

namespace Olix\BackOfficeBundle\Security;

use Olix\BackOfficeBundle\Model\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class UserChecker implements UserCheckerInterface
{

    /**
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
    }


    /**
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // user account is not activ
        if ( ! $user->isEnabled() ) {
            throw new CustomUserMessageAccountStatusException('Account has disabled.');
        }

        // user account is expired, the user may be notified
        if ( $user->isExpired() ) {
            throw new CustomUserMessageAccountStatusException('Account has expired.');
        }
    }

}
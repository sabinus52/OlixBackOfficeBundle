<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Security;

use Olix\BackOfficeBundle\Model\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Classe pour checker l'autorisation des utilisateurs.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @see        https://symfony.com/doc/current/security/user_checkers.html
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // user account is not activ
        if (!$user->isEnabled()) {
            throw new CustomUserMessageAccountStatusException('Account has disabled.');
        }

        // user account is expired, the user may be notified
        if ($user->isExpired()) {
            throw new CustomUserMessageAccountStatusException('Account has expired.');
        }
    }
}

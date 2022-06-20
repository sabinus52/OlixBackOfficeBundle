<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Datatable;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @see https://github.com/stwe/DatatablesBundle
 * @SuppressWarnings(PHPMD)
 */
class DatatableFactory
{
    /**
     * The AuthorizationChecker service.
     *
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * The SecurityTokenStorage service.
     *
     * @var TokenStorageInterface
     */
    protected $securityToken;

    /**
     * The Translator service.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * The Router service.
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * The doctrine orm entity manager service.
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * The Twig Environment.
     *
     * @var Environment
     */
    protected $twig;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        TranslatorInterface $translator,
        RouterInterface $router,
        EntityManagerInterface $em,
        Environment $twig
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->securityToken = $securityToken;

        if (!($translator instanceof TranslatorInterface)) {
            throw new \InvalidArgumentException(sprintf('The $translator argument of %s must be an instance of %s, a %s was given.', static::class, TranslatorInterface::class, \get_class($translator)));
        }
        $this->translator = $translator;
        $this->router = $router;
        $this->em = $em;
        $this->twig = $twig;
    }

    // -------------------------------------------------
    // Create Datatable
    // -------------------------------------------------

    /**
     * @param string $class
     *
     * @throws Exception
     *
     * @return DatatableInterface
     */
    public function create($class)
    {
        if (!\is_string($class)) {
            $type = \gettype($class);

            throw new Exception("DatatableFactory::create(): String expected, {$type} given");
        }

        if (false === class_exists($class)) {
            throw new Exception("DatatableFactory::create(): {$class} does not exist");
        }

        // @phpstan-ignore-next-line
        if (\in_array(DatatableInterface::class, class_implements($class), true)) {
            // @phpstan-ignore-next-line
            return new $class(
                $this->authorizationChecker,
                $this->securityToken,
                $this->translator,
                $this->router,
                $this->em,
                $this->twig
            );
        }

        throw new Exception("DatatableFactory::create(): The class {$class} should implement the DatatableInterface.");
    }
}

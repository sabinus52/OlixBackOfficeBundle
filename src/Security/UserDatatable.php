<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Olix\BackOfficeBundle\Datatable\AbstractDatatable;
use Olix\BackOfficeBundle\Datatable\Column\ActionColumn;
use Olix\BackOfficeBundle\Datatable\Column\Column;
use Olix\BackOfficeBundle\Datatable\Column\VirtualColumn;
use Olix\BackOfficeBundle\Model\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Classe de la Datatable de la liste des utilisateurs.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
class UserDatatable extends AbstractDatatable
{
    /**
     * Entité des utilisateurs "security.class.user" (olix_bo.yaml).
     *
     * @var string
     */
    private $entityUser;

    /**
     * Delai en minutes à partir duquel l'utilisateur est considéré comme non connecté.
     *
     * @var int
     */
    private $delayActivity;

    /**
     * Constructeur
     * {@inheritdoc}
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        TranslatorInterface $translator,
        RouterInterface $router,
        EntityManagerInterface $entityManager,
        Environment $twig,
        array $options = []
    ) {
        // Affecte l'entité de la classe User à utiliser pour le Datatable
        $this->entityUser = $options['entityUser'];

        parent::__construct($authorizationChecker, $securityToken, $translator, $router, $entityManager, $twig);
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function ($line) {
            /** @var User $user */
            $user = new $this->entityUser();
            $user
                ->setAvatar($line['avatar'])
                ->setEnabled((bool) $line['enabled'])
                ->setExpiresAt($line['expiresAt'])
                ->setLastActivity($line['lastActivity'])
                ->setLastLogin($line['lastLogin'])
            ;

            $line['avatar'] = '<img src="'.$user->getAvatar('/').'">';
            $line['online'] = $user->getOnlineBadge($this->delayActivity);
            $line['state'] = $user->getStateBadge();
            $line['lastLogin'] = '';
            if ($user->getLastLogin()) {
                $line['lastLogin'] = $user->getLastLogin()->format('d/m/Y H:i').' ('.$user->getIntervalLastLogin().')';
            }

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     *
     * @param array<mixed> $options
     */
    public function buildDatatable(array $options = []): void
    {
        $this->delayActivity = $options['delay'];

        $this->ajax->set([]);

        $this->options->set([
            'order' => [[2, 'asc']],
        ]);

        $this->columnBuilder
            ->add('avatar', Column::class, [
                'title' => '',
                'class_name' => 'avatar text-center',
                'orderable' => false,
            ])
            ->add('online', VirtualColumn::class, [
                'title' => 'Connecté',
                'class_name' => 'text-center',
            ])
            ->add('lastActivity', Column::class, [
                'visible' => false,
            ])
            ->add('username', Column::class, [
                'title' => 'Utilisateur',
                'searchable' => true,
            ])
            ->add('name', Column::class, [
                'title' => 'Nom',
                'searchable' => true,
            ])
            ->add('email', Column::class, [
                'title' => 'Email',
                'searchable' => true,
            ])
            ->add('state', VirtualColumn::class, [
                'title' => 'Statut',
                'class_name' => 'text-center',
            ])
            ->add('enabled', Column::class, [
                'visible' => false,
            ])
            ->add('expiresAt', Column::class, [
                'visible' => false,
            ])
            ->add('lastLogin', Column::class, [
                'title' => 'Dernière connexion',
            ])
            ->add(null, ActionColumn::class, [
                'actions' => [
                    [
                        'icon' => 'fas fa-key',
                        'label' => 'Mot de passe',
                        'route' => 'olix_users__password',
                        'route_parameters' => [
                            'id' => 'id',
                        ],
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => 'Modifier le mot de passe',
                            'class' => 'btn btn-secondary btn-sm',
                            'role' => 'button',
                        ],
                    ],
                    [
                        'icon' => 'fas fa-pencil-alt',
                        'label' => 'Modifier',
                        'route' => 'olix_users__edit',
                        'route_parameters' => [
                            'id' => 'id',
                        ],
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => 'Modifier l\'utilisateur',
                            'class' => 'btn btn-info btn-sm',
                            'role' => 'button',
                        ],
                    ],
                    [
                        'icon' => 'fas fa-trash',
                        'label' => 'Supprimer',
                        'route' => 'olix_users__remove',
                        'route_parameters' => [
                            'id' => 'id',
                        ],
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => 'Supprimer l\'utilisateur',
                            'class' => 'btn btn-danger btn-sm obtn-delete',
                            'role' => 'button',
                        ],
                    ],
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity(): string
    {
        return $this->entityUser;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'user_datatable';
    }
}

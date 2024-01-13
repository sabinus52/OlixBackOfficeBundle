<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Security;

use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

/**
 * Datatables de la liste des serveurs.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
class UserDatatable implements DataTableTypeInterface
{
    /**
     * DataTable des utilisateurs.
     *
     * @param DataTable    $dataTable
     * @param array<mixed> $options
     */
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable
            ->add('avatar', TextColumn::class, [
                'label' => '',
                'className' => 'avatar text-center',
                'raw' => true,
                'data' => static fn ($row) => sprintf('<img src="%s">', $row->getAvatar('/')),
            ])
            ->add('online', TextColumn::class, [
                'label' => 'Connecté',
                'className' => 'text-center',
                'raw' => true,
                'data' => static fn ($row) => $row->getOnlineBadge($options['delay']),
            ])
            ->add('username', TextColumn::class, [
                'label' => 'Utilisateur',
                'searchable' => true,
            ])
            ->add('name', TextColumn::class, [
                'label' => 'Nom',
                'searchable' => true,
            ])
            ->add('email', TextColumn::class, [
                'label' => 'Email',
                'searchable' => true,
            ])
            ->add('state', TextColumn::class, [
                'label' => 'Statut',
                'className' => 'text-center',
                'raw' => true,
                'data' => static fn ($row) => $row->getStateBadge(),
            ])
            ->add('enabled', TextColumn::class, [
                'visible' => false,
            ])
            ->add('expiresAt', DateTimeColumn::class, [
                'visible' => false,
            ])
            ->add('lastLogin', DateTimeColumn::class, [
                'label' => 'Dernière connexion',
                'format' => 'd/m/Y',
            ])
            ->add('buttons', TwigColumn::class, [
                'label' => '',
                'className' => 'text-right align-middle',
                'template' => '@OlixBackOffice/Security/users-buttonbar.html.twig',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => $options['entity'],
            ])
            ->addOrderBy('username', DataTable::SORT_ASCENDING)
        ;
    }
}

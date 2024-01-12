<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Classe de service de l'autocomplétion des objets Select2
 * Permet de retourner les items en AJAX.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
class AutoCompleteService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Constructeur.
     */
    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * Retourne les resultats trouvés depuis un recherche "Select2".
     *
     * @return array<mixed>
     */
    public function getResults(string $formType, Request $request): array
    {
        $accessor = new PropertyAccessor();

        // Paramètres du Request
        $term = $request->get('term');
        $page = (int) $request->get('page', 0);
        $widget = $request->get('widget');

        // Info du formulaire en cours utilisé
        $form = $this->formFactory->create($formType);
        $select2Options = $form->get($widget)->getConfig()->getOptions();
        $count = $select2Options['page_limit'];

        // Recherche des items
        $query = $this->entityManager->createQueryBuilder()
            ->select('entity')
            ->from($select2Options['class'], 'entity')
            ->andWhere('entity.'.$select2Options['class_property'].' LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('entity.'.$select2Options['class_property'], 'ASC')
        ;

        // Si tous les items ou bien par page
        if (0 === $page) {
            $query = $query->getQuery();
            $items = $query->getResult();
        } else {
            $query = $query->setFirstResult(($page - 1) * $count)
                ->setMaxResults($count)
                ->getQuery()
            ;
            $items = new Paginator($query, true);
        }

        // Mapping des resultats
        $results = [];
        foreach ($items as $item) {
            $results[] = [
                'id' => $accessor->getValue($item, $select2Options['class_pkey']),
                'text' => $accessor->getValue($item, $select2Options['class_label']),
            ];
        }

        // Retourne les resultats pagninés
        if (0 !== $page) {
            return [
                'results' => $results,
                'more' => (($page * $count) < count($items)),
            ];
        }

        // Retourne tous les résultats
        return $results;
    }
}

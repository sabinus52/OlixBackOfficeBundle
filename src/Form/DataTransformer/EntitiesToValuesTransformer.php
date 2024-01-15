<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Classe de transformation entre une entité et les valeurs de la liste multiple "Select2".
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 */
class EntitiesToValuesTransformer implements DataTransformerInterface
{
    protected PropertyAccessor $accessor;

    /**
     * Constructeur.
     *
     * @param string $entityName : Nom de la classe de l'entité
     * @param string $primaryKey : Clé primaire de l'entité de la valeur de la liste de choix
     * @param string $fieldLabel : Label de la valeur correspondant à un champs de l'entité
     */
    public function __construct(protected EntityManagerInterface $entityManager, protected string $entityName, protected string $primaryKey, protected string $fieldLabel)
    {
        $this->accessor = new PropertyAccessor();
    }

    /**
     * {@inheritDoc}
     */
    public function transform($entities): mixed
    {
        $result = [];
        if (empty($entities)) {
            return $result;
        }

        foreach ($entities as $entity) {
            $value = $this->accessor->getValue($entity, $this->primaryKey);
            $text = $this->accessor->getValue($entity, $this->fieldLabel);
            $result[$value] = $text;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($values): mixed
    {
        if (empty($values) || !is_array($values)) {
            return [];
        }

        $entities = $this->entityManager->createQueryBuilder()
            ->select('entity')
            ->from($this->entityName, 'entity')
            ->where('entity.'.$this->primaryKey.' IN (:ids)')
            ->setParameter('ids', $values)
            ->getQuery()
            ->getResult()
        ;

        if (count($entities) !== count($values)) {
            throw new TransformationFailedException('One or more id values are invalid');
        }

        return $entities;
    }
}

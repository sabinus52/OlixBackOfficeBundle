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
     * @param string $entityName     : Nom de la classe de l'entité
     * @param string $primaryKey     : Clé primaire de l'entité de la valeur de la liste de choix
     * @param string $fieldLabel     : Label de la valeur correspondant à un champs de l'entité
     * @param string $prefixAllowAdd : Prefix des nouveaux tags pour les déterminer
     */
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected string $entityName,
        protected string $primaryKey,
        protected ?string $fieldLabel,
        protected string $prefixAllowAdd)
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
            $text = (null === $this->fieldLabel) ? (string) $entity : $this->accessor->getValue($entity, $this->fieldLabel);

            if ($this->entityManager->contains($entity)) {
                $value = $this->accessor->getValue($entity, $this->primaryKey);
            } else {
                $value = $this->prefixAllowAdd.$text;
            }

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

        $newEntities = [];
        $prefixLength = strlen($this->prefixAllowAdd);
        // Création des nouveaux items
        foreach ($values as $key => $value) {
            $realValue = substr((string) $value, $prefixLength);
            $prefix = substr((string) $value, 0, $prefixLength);
            if ($prefix === $this->prefixAllowAdd) {
                $newObject = new $this->entityName();
                $this->accessor->setValue($newObject, $this->fieldLabel, $realValue);
                $newEntities[] = $newObject;
                unset($values[$key]);
            }
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

        return array_merge($entities, $newEntities);
    }
}

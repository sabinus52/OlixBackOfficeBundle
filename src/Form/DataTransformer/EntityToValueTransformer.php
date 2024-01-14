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
use Doctrine\ORM\UnexpectedResultException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Classe de transformation entre une entité et la valeur de la liste de choix "Select2".
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 */
class EntityToValueTransformer implements DataTransformerInterface
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
    public function transform($entity)
    {
        $result = [];
        if (empty($entity)) {
            return $result;
        }

        $value = $this->accessor->getValue($entity, $this->primaryKey);
        $text = $this->accessor->getValue($entity, $this->fieldLabel);
        $result[$value] = $text;

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            $entity = $this->entityManager->createQueryBuilder()
                ->select('entity')
                ->from($this->entityName, 'entity')
                ->where('entity.'.$this->primaryKey.' = :id')
                ->setParameter('id', $value)
                ->getQuery()
                ->getSingleResult()
            ;
        } catch (UnexpectedResultException) {
            throw new TransformationFailedException(sprintf('The choice "%s" does not exist or is not unique', $value));
        }

        if (!$entity) {
            return null;
        }

        return $entity;
    }
}

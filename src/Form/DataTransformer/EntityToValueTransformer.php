<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 *
 * @phpstan-ignore missingType.generics
 */
class EntityToValueTransformer implements DataTransformerInterface
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
        protected string $fieldLabel,
        protected string $prefixAllowAdd,
    ) {
        $this->accessor = new PropertyAccessor();
    }

    /**
     * @param object $entity
     */
    #[\Override]
    public function transform($entity): mixed
    {
        $result = [];
        if (!is_object($entity)) {
            return $result;
        }

        $text = $this->accessor->getValue($entity, $this->fieldLabel);

        if ($this->entityManager->contains($entity)) {
            $value = $this->accessor->getValue($entity, $this->primaryKey);
        } else {
            $value = $this->prefixAllowAdd.$text;
        }

        $result[$value] = $text;

        return $result;
    }

    #[\Override]
    public function reverseTransform($value): mixed
    {
        if (empty($value)) {
            return null;
        }

        // Vérifie si ce n'est pas une nouvelle valeur potentielle
        $prefixLength = strlen($this->prefixAllowAdd);
        $prefix = substr((string) $value, 0, $prefixLength);
        if ($prefix === $this->prefixAllowAdd) {
            $realValue = substr((string) $value, $prefixLength);
            $entity = new $this->entityName();
            $this->accessor->setValue($entity, $this->fieldLabel, $realValue);
        } else {
            // Valeur choisie dans la liste
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
                throw new TransformationFailedException(sprintf('The choice "%s" does not exist or is not unique', (string) $value));
            }
        }

        if (!$entity) {
            return null;
        }

        return $entity;
    }
}

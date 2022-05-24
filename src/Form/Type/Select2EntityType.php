<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Type;

use Olix\BackOfficeBundle\Form\Model\Select2ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Widget de formulaire de type select amélioré depuis les données d'une entité.
 *
 * @example     Configuration with options of this type
 * @example     @param Enum   color      : Couleur du widget
 * @example     Config widget with the parameter 'attr' : { params }
 * @example     @param string data-placeholder        : Specifies the placeholder for the control.
 * @example     @param int    data-minimumInputLength : Minimum number of characters required to start a search.
 * @example     @see https://select2.org/configuration/options-api Liste des différentes options
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         SwitchModelType::class
 */
class Select2EntityType extends Select2ModelType
{
    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return EntityType::class;
    }
}

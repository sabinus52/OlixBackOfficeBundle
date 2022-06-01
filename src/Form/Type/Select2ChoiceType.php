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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Widget de formulaire de type select amélioré depuis une liste de valeurs.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         Select2ModelType::class
 */
class Select2ChoiceType extends Select2ModelType
{
    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}

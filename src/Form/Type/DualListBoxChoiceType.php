<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Type;

use Olix\BackOfficeBundle\Form\Model\DualListBoxModelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Widget de formulaire de selection multiple en double liste depuis une liste de valeurs.
 *
 * @example     Configuration with surcharge javascript
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         DualListBoxModelType::class
 */
class DualListBoxChoiceType extends DualListBoxModelType
{
    #[\Override]
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}

<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Type;

use Olix\BackOfficeBundle\Form\Model\SwitchModelType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Widget de formulaire de type switch équivalent à une case à cocher.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @see        SwitchModelType::class
 */
class SwitchType extends SwitchModelType
{
    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return CheckboxType::class;
    }
}

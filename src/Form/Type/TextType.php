<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Type;

use Olix\BackOfficeBundle\Form\Model\InputModelType;
use Symfony\Component\Form\Extension\Core\Type\TextType as SfTextType;

/**
 * Widget de formulaire de type "Text" amélioré.
 *
 * @example     Configuration with options of this type
 * @example     @see InputModelType::class
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         InputModelType::class
 */
class TextType extends InputModelType
{
    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return SfTextType::class;
    }
}

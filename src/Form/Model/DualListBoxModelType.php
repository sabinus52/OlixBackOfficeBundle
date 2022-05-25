<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de selection multiple en double liste.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/istvan-ujjmeszaros/bootstrap-duallistbox
 */
abstract class DualListBoxModelType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'multiple' => true,
            'expanded' => false,
        ]);

        $resolver->setAllowedValues('multiple', [true]);
        $resolver->setAllowedValues('expanded', [false]);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_duallistbox';
    }
}

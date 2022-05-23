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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type switch équivalent à une case à cocher.
 *
 * @example     Config with parameter 'attr' : { params }
 * @example     @param string data-on-color  : "success"
 * @example     @param string data-off-color : "danger"
 * @example     @param string data-on-text   : "OUI"
 * @example     @param string data-on-text   : "NON"
 * @example     @param enum   data-size      : (small|mini|normal|large)
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/Bttstrp/bootstrap-switch
 */
abstract class SwitchModelType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return CheckboxType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_switch';
    }
}

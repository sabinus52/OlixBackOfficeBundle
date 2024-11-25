<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type input.
 *
 * @example     Configuration with options of this type
 * @example     @param string   left_symbol     : Symbole à gauche du widget
 * @example     @param string   right_symbol    : Symbole à droite du widget
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
abstract class InputModelType extends AbstractModelType
{
    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'left_symbol' => null,
            'right_symbol' => null,
        ]);

        $resolver->setAllowedTypes('left_symbol', ['null', 'string']);
        $resolver->setAllowedTypes('right_symbol', ['null', 'string']);
    }

    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // pass the form type option directly to the template
        $view->vars['left_symbol'] = $options['left_symbol'];
        $view->vars['right_symbol'] = $options['right_symbol'];
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_input';
    }
}

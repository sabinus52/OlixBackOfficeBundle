<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de selection multiple en double liste.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://www.npmjs.com/package/bootstrap4-duallistbox
 *
 * @version     4.*
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
abstract class DualListBoxModelType extends AbstractType
{
    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'multiple' => true,
            'expanded' => false,
            'options_js' => [],
        ]);

        $resolver->setAllowedValues('multiple', [true]);
        $resolver->setAllowedValues('expanded', [false]);
        // Options supplémentaires JavaScript du widget
        $resolver->setAllowedTypes('options_js', ['array']);
    }

    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Sélecteur du widget
        $view->vars['attr'] += ['data-toggle' => 'duallistbox'];

        // Options javascript du widget
        $view->vars['attr'] += ['data-options-js' => json_encode($options['options_js'])];
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_duallistbox';
    }
}

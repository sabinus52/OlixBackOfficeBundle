<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Olix\BackOfficeBundle\Enum\ColorCSS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type select amélioré.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/select2/select2
 * @see         Liste des différentes options : https://select2.org/configuration/options-api
 *
 * @version     4.1
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
abstract class Select2ModelType extends AbstractType
{
    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'expanded' => false,
            'color' => null,       // Couleur du widget
            'options_js' => [],
        ]);

        $resolver->setAllowedValues('expanded', [false]);
        $resolver->setAllowedTypes('color', ['null', 'string']);
        $resolver->setAllowedValues('color', [null] + ColorCSS::values());
        // Options supplémentaires JavaScript du widget
        $resolver->setAllowedTypes('options_js', ['array']);
    }

    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Couleur du widget
        $view->vars['color'] = (null !== $options['color']) ? $options['color'] : 'default';

        // Sélecteur du widget
        $view->vars['attr'] += ['data-toggle' => 'select2'];

        // Options javascript du widget
        $view->vars['attr'] += ['data-options-js' => json_encode($options['options_js'])];
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_select2';
    }
}

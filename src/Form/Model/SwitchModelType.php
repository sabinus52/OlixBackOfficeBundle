<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Olix\BackOfficeBundle\Enum\ColorBS;
use Olix\BackOfficeBundle\Enum\ColorCSS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type switch équivalent à une case à cocher.
 *
 * @see         https://codepen.io/claviska/pen/KyWmjY
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
abstract class SwitchModelType extends AbstractType
{
    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options CSS supplémentaires du widget
        $resolver->setDefaults([
            'on_color' => null,     // Couleur du widget switch dans l'état checked
            'off_color' => null,    // Couleur du widget switch dans l'état non checked
            'size' => null,         // Dimension du widget switch
            'chk_label' => '',      // Label du switch à droite du widget
        ]);

        $resolver->setAllowedTypes('on_color', ['null', 'string']);
        $resolver->setAllowedTypes('off_color', ['null', 'string']);
        $resolver->setAllowedTypes('size', ['null', 'string']);
        $resolver->setAllowedTypes('chk_label', ['string']);
        $resolver->setAllowedValues('on_color', [null, ColorCSS::INDIGO->value] + ColorBS::values());
        $resolver->setAllowedValues('off_color', [null, ColorCSS::INDIGO->value] + ColorBS::values());
        $resolver->setAllowedValues('size', [null, 'small', 'large']);
    }

    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Options attributes du widget
        $view->vars['chk_label'] = $options['chk_label'];

        $view->vars['class_switch'] = ['switch'];
        if (null !== $options['on_color']) {
            $view->vars['class_switch'][] = sprintf('switch-on-%s', (string) $options['on_color']); // @phpstan-ignore cast.string
        }
        if (null !== $options['off_color']) {
            $view->vars['class_switch'][] = sprintf('switch-off-%s', (string) $options['off_color']); // @phpstan-ignore cast.string
        }
        if (null !== $options['size']) {
            $view->vars['class_switch'][] = sprintf('switch-%s', (string) $options['size']); // @phpstan-ignore cast.string
        }
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_switch';
    }
}

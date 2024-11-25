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
 * Widget de formulaire de type switch équivalent à une case à cocher.
 *
 * @example     Configuration with options of this type
 * @example     @param bool   js_inverse       : Inverse switch direction
 * @example     @param string js_on_color      : Color of the left side of the switch
 * @example     @param string js_off_color     : Color of the right side of the switch
 * @example     @param string js_on_text       : Text of the left side of the switch
 * @example     @param string js_on_text       : Text of the right side of the switch
 * @example     @param string js_size          : The checkbox size
 * @example     @param bool   js_indeterminate : Indeterminate state
 * @example     @param string js_label_text    : Text of the center handle of the switch
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/Bttstrp/bootstrap-switch
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
abstract class SwitchModelType extends AbstractModelType
{
    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options JavaScript supplémentaires du widget
        $resolver->setDefaults([
            'on_color' => null,
            'off_color' => null,
            'chk_label' => '',
        ]);

        $resolver->setAllowedTypes('on_color', ['null', 'string']);
        $resolver->setAllowedTypes('off_color', ['null', 'string']);
        $resolver->setAllowedTypes('chk_label', ['string']);
    }

    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Options attributes du widget
        $view->vars['chk_label'] = $options['chk_label'];

        $view->vars['class_color'] = ['custom-control', 'custom-switch'];
        if (null !== $options['on_color']) {
            $view->vars['class_color'][] = sprintf('custom-switch-on-%s', (string) $options['on_color']);
        }
        if (null !== $options['off_color']) {
            $view->vars['class_color'][] = sprintf('custom-switch-off-%s', (string) $options['off_color']);
        }
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_switch';
    }
}

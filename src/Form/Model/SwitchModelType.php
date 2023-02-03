<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
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
 */
abstract class SwitchModelType extends AbstractModelType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options JavaScript supplémentaires du widget
        $resolver->setDefaults([
            'js_inverse' => false,
            'js_on_color' => 'primary',
            'js_off_color' => 'default',
            'js_on_text' => 'OUI',
            'js_off_text' => 'NON',
            'js_size' => 'normal',
            'js_indeterminate' => false,
            'js_label_text' => '&nbsp;',
        ]);

        $resolver->setAllowedTypes('js_inverse', ['bool']);
        $resolver->setAllowedTypes('js_on_color', ['string']);
        $resolver->setAllowedTypes('js_off_color', ['string']);
        $resolver->setAllowedTypes('js_on_text', ['string']);
        $resolver->setAllowedTypes('js_off_text', ['string']);
        $resolver->setAllowedTypes('js_size', ['null', 'string']);
        $resolver->setAllowedTypes('js_indeterminate', ['bool']);
        $resolver->setAllowedTypes('js_label_text', ['string']);

        $resolver->setAllowedValues('js_on_color', self::COLORS);
        $resolver->setAllowedValues('js_off_color', self::COLORS);
        $resolver->setAllowedValues('js_size', ['mini', 'small', 'normal', 'large']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Options attributes du widget
        $view->vars['attr'] += ['data-toggle' => 'switch'];
        $view->vars['attr'] += ['data-options-js' => json_encode($this->getOptionsWidgetCamelized($options))];
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_switch';
    }
}

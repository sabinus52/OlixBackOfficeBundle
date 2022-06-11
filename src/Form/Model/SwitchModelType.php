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
 * @example     @param bool   ojs_inverse       : Inverse switch direction
 * @example     @param string ojs_on_color      : Color of the left side of the switch
 * @example     @param string ojs_off_color     : Color of the right side of the switch
 * @example     @param string ojs_on_text       : Text of the left side of the switch
 * @example     @param string ojs_on_text       : Text of the right side of the switch
 * @example     @param string ojs_size          : The checkbox size
 * @example     @param bool   ojs_indeterminate : Indeterminate state
 * @example     @param string ojs_label_text    : Text of the center handle of the switch
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
            'ojs_inverse' => false,
            'ojs_on_color' => 'primary',
            'ojs_off_color' => 'default',
            'ojs_on_text' => 'OUI',
            'ojs_off_text' => 'NON',
            'ojs_size' => 'normal',
            'ojs_indeterminate' => false,
            'ojs_label_text' => '&nbsp;',
        ]);

        $resolver->setAllowedTypes('ojs_inverse', ['bool']);
        $resolver->setAllowedTypes('ojs_on_color', ['string']);
        $resolver->setAllowedTypes('ojs_off_color', ['string']);
        $resolver->setAllowedTypes('ojs_on_text', ['string']);
        $resolver->setAllowedTypes('ojs_off_text', ['string']);
        $resolver->setAllowedTypes('ojs_size', ['null', 'string']);
        $resolver->setAllowedTypes('ojs_indeterminate', ['bool']);
        $resolver->setAllowedTypes('ojs_label_text', ['string']);

        $resolver->setAllowedValues('ojs_on_color', self::COLORS);
        $resolver->setAllowedValues('ojs_off_color', self::COLORS);
        $resolver->setAllowedValues('ojs_size', ['mini', 'small', 'normal', 'large']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Options javascript du widget
        $view->vars['ojs_options'] = $this->getOptionsWidgetCamelized($options);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_switch';
    }
}

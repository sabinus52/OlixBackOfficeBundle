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
 * Widget de formulaire de type select amélioré.
 *
 * @example     Configuration with options of this type
 * @example     @param Enum   color      : Couleur du widget
 * @example     Config widget width JS parameters
 * @example     @param bool   js_allow_clear           : Causes a clear button ("x" icon) to appear on the select box when a value is selected
 * @example     @param bool   js_close_on_select       : Select2 will automatically close the dropdown when an element is selected
 * @example     @param string js_language              : Specify the language used for Select2 messages
 * @example     @param string js_placeholder           : Specifies the placeholder for the control.
 * @example     @param int    js_minimum_input_length  : Minimum number of characters required to start a search.               :
 * @example     @see https://select2.org/configuration/options-api Liste des différentes options
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/select2/select2
 */
abstract class Select2ModelType extends AbstractModelType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'expanded' => false,
            'color' => 'default',
        ]);

        $resolver->setAllowedValues('expanded', [false]);
        $resolver->setAllowedTypes('color', ['string']);
        $resolver->setAllowedValues('color', self::COLORS);

        // Options JavaScript supplémentaires du widget
        $resolver->setDefaults([
            'js_allow_clear' => false,
            'js_close_on_select' => true,
            'js_language' => 'fr',
            'js_minimum_input_length' => 0,
            'js_placeholder' => '',
            'js_width' => '100%',
        ]);

        $resolver->setAllowedTypes('js_allow_clear', ['bool']);
        $resolver->setAllowedTypes('js_close_on_select', ['bool']);
        $resolver->setAllowedTypes('js_language', ['string']);
        $resolver->setAllowedTypes('js_minimum_input_length', ['int']);
        $resolver->setAllowedTypes('js_placeholder', ['string']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // pass the form type option directly to the template
        $view->vars['color'] = $options['color'];

        // Options attributes du widget
        $view->vars['attr'] += ['data-toggle' => 'select2'];
        $view->vars['attr'] += ['data-options-js' => json_encode($this->getOptionsWidgetCamelized($options))];
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_select2';
    }
}

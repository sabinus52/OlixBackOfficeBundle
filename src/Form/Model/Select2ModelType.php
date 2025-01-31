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
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
abstract class Select2ModelType extends AbstractModelType
{
    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'expanded' => false,
            'color' => 'default',
            self::KEY_OPTS_JS => [],
        ]);

        $resolver->setAllowedValues('expanded', [false]);
        $resolver->setAllowedTypes('color', ['string']);
        $resolver->setAllowedValues('color', self::COLORS);
        // Options supplémentaires JavaScript du widget
        $resolver->setAllowedTypes(self::KEY_OPTS_JS, ['array']);
    }

    /**
     * @param array<string,array<string,mixed>> $options
     */
    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Couleur du widget
        $view->vars['color'] = $options['color'];

        // Sélecteur du widget
        $view->vars['attr'] += [self::ATTR_DATA_SELECTOR => 'select2'];

        // Options javascript du widget
        /** @var array<string, mixed> $optionsJavaScript */
        $optionsJavaScript = $options[self::KEY_OPTS_JS];
        $view->vars['attr'] += [self::ATTR_DATA_OPTIONS => json_encode($this->getOptionsWidgetCamelized($optionsJavaScript))];
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_select2';
    }
}

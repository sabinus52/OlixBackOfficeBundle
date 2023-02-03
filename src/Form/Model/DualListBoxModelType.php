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
 * Widget de formulaire de selection multiple en double liste.
 *
 * @example     Configuration with options of this type
 * @example     @param string       js_filter_text_clear       : The text for the "Show All" button
 * @example     @param string       js_filter_place_holder     : The placeholder for the input element for filtering elements
 * @example     @param string       js_move_selected_label     : The label for the "Move Selected" button
 * @example     @param string       js_move_all_label          : The label for the "Move All" button
 * @example     @param string       js_remove_selected_label   : The label for the "Remove Selected" button
 * @example     @param string       js_remove_all_label        : The label for the "Remove All" button
 * @example     @param string|bool  js_selected_list_label     : Can be a string specifying the name of the selected list
 * @example     @param string|bool  js_non_selected_list_label : Can be a string specifying the name of the non selected list
 * @example     @param int          js_selector_minimal_height : Represents the minimal height of the generated dual listbox
 * @example     @param bool         js_show_filter_inputs      : Whether to show filter input
 * @example     @param string       js_non_selected_filter     : Initializes the dual listbox with a filter for the non selected elements
 * @example     @param string       js_selected_filter         : Initializes the dual listbox with a filter for the selected elements
 * @example     @param string|bool  js_info_text               : Set this to false to hide this information
 * @example     @param string       js_info_text_filtered      : Determines which element format to use when some element is filtered
 * @example     @param string       js_info_text_empty         : Determines the string to use when there are no options in the list
 * @example     @param string       js_filter_on_values        : Set this to true to filter the options according to their values
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/istvan-ujjmeszaros/bootstrap-duallistbox
 */
abstract class DualListBoxModelType extends AbstractModelType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'multiple' => true,
            'expanded' => false,
        ]);

        $resolver->setAllowedValues('multiple', [true]);
        $resolver->setAllowedValues('expanded', [false]);

        // Options JavaScript supplémentaires du widget
        $resolver->setDefaults([
            'js_filter_text_clear' => 'voir tous',
            'js_filter_place_holder' => 'Filtrer',
            'js_move_selected_label' => 'Déplacer la sélection',
            'js_move_all_label' => 'Déplacer tous',
            'js_remove_selected_label' => 'Supprimer la sélection',
            'js_remove_all_label' => 'Supprimer tous',
            'js_selected_list_label' => false,
            'js_non_selected_list_label' => false,
            'js_selector_minimal_height' => 100,
            'js_show_filter_inputs' => true,
            'js_non_selected_filter' => '',
            'js_selected_filter' => '',
            'js_info_text' => 'Voir tous {0}',
            'js_info_text_filtered' => '<span class="badge badge-warning">Filtré</span> {0} sur {1}',
            'js_info_text_empty' => 'Liste vide',
            'js_filter_on_values' => false,
        ]);

        $resolver->setAllowedTypes('js_filter_text_clear', ['string']);
        $resolver->setAllowedTypes('js_filter_place_holder', ['string']);
        $resolver->setAllowedTypes('js_move_selected_label', ['string']);
        $resolver->setAllowedTypes('js_move_all_label', ['string']);
        $resolver->setAllowedTypes('js_remove_selected_label', ['string']);
        $resolver->setAllowedTypes('js_remove_all_label', ['string']);
        $resolver->setAllowedTypes('js_selected_list_label', ['bool', 'string']);
        $resolver->setAllowedTypes('js_non_selected_list_label', ['bool', 'string']);
        $resolver->setAllowedTypes('js_selector_minimal_height', ['int']);
        $resolver->setAllowedTypes('js_show_filter_inputs', ['bool']);
        $resolver->setAllowedTypes('js_non_selected_filter', ['string']);
        $resolver->setAllowedTypes('js_selected_filter', ['string']);
        $resolver->setAllowedTypes('js_info_text', ['string', 'bool']);
        $resolver->setAllowedTypes('js_info_text_filtered', ['string']);
        $resolver->setAllowedTypes('js_info_text_empty', ['string']);
        $resolver->setAllowedTypes('js_filter_on_values', ['bool']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Options javascript du widget
        $view->vars['attr'] += ['data-toggle' => 'duallistbox'];
        $view->vars['attr'] += ['data-options-js' => json_encode($this->getOptionsWidgetCamelized($options))];
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_duallistbox';
    }
}

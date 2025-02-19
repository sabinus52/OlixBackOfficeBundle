<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Olix\BackOfficeBundle\Helper\Helper;
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
 * @SuppressWarnings(PHPMD.StaticAccess)
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

        /**
         * @deprecated 1.2 : Options JavaScript du widget
         */
        $resolver->setDefined([
            'js_filter_text_clear',
            'js_filter_place_holder',
            'js_move_selected_label',
            'js_move_all_label',
            'js_remove_selected_label',
            'js_remove_all_label',
            'js_selected_list_label',
            'js_non_selected_list_label',
            'js_selector_minimal_height',
            'js_show_filter_inputs',
            'js_non_selected_filter',
            'js_selected_filter',
            'js_info_text',
            'js_info_text_filtered',
            'js_info_text_empty',
            'js_filter_on_values',
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

        $resolver->setDeprecated('js_filter_text_clear', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "filterTextClear" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_filter_place_holder', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "filterPlaceHolder" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_move_selected_label', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "moveSelectedLabel" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_move_all_label', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "moveAllLabel" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_remove_selected_label', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "removeSelectedLabel" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_remove_all_label', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "removeAllLabel" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_selected_list_label', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "selectedListLabel" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_non_selected_list_label', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "nonSelectedListLabel" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_selector_minimal_height', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "selectorMinimalHeight" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_show_filter_inputs', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "showFilterInputs" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_non_selected_filter', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "nonSelectedFilter" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_selected_filter', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "selectedFilter" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_info_text', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "infoText" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_info_text_filtered', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "infoTextFiltered" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_info_text_empty', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "infoTextEmpty" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_filter_on_values', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "filterOnValues" option of the "options_js" option instead.');
    }

    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Sélecteur du widget
        $view->vars['attr'] += ['data-toggle' => 'duallistbox'];

        // Options javascript du widget
        $optionsJavaScriptDeprecated = Helper::getCamelizedKeys($options, 'js_'); /** @deprecated 1.2 */
        $view->vars['attr'] += ['data-options-js' => json_encode($options['options_js'] + $optionsJavaScriptDeprecated)];
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_duallistbox';
    }
}

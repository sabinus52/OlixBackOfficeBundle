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
 * @example     @param bool   ojs_allow_clear           : Causes a clear button ("x" icon) to appear on the select box when a value is selected
 * @example     @param bool   ojs_close_on_select       : Select2 will automatically close the dropdown when an element is selected
 * @example     @param string ojs_language              : Specify the language used for Select2 messages
 * @example     @param string ojs_placeholder           : Specifies the placeholder for the control.
 * @example     @param int    ojs_minimum_input_length  : Minimum number of characters required to start a search.
 * @example     @param string ojs_template_result       : Customizes the way that search results are rendered
 * @example     @param string ojs_template_selection    : Customizes the way that selections are rendered
 * @example     Config for ajax remote datas
 * @example     @param string ajax_route                : Route of ajax remote datas
 * @example     @param array  ajax_param                : Parameters of route
 * @example     @param bool   ajax_scroll               : "infinite scrolling" for remote data sources out of the box
 * @example     @param int    ajax_delay                : The number of milliseconds to wait for the user to stop typing before issuing the ajax request
 * @example     @param bool   ajax_cache                :
 * @example     @see https://select2.org/configuration/options-api Liste des différentes options
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/select2/select2
 * @see         https://github.com/tetranz/select2entity-bundle (inspiration)
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
            'ojs_allow_clear' => false,
            'ojs_close_on_select' => true,
            'ojs_language' => 'fr',
            'ojs_minimum_input_length' => 0,
            'ojs_placeholder' => '',
            'ojs_template_result' => null,
            'ojs_template_selection' => null,
        ]);

        $resolver->setAllowedTypes('ojs_allow_clear', ['bool']);
        $resolver->setAllowedTypes('ojs_close_on_select', ['bool']);
        $resolver->setAllowedTypes('ojs_language', ['string']);
        $resolver->setAllowedTypes('ojs_minimum_input_length', ['int']);
        $resolver->setAllowedTypes('ojs_placeholder', ['string']);
        $resolver->setAllowedTypes('ojs_template_result', ['null', 'string']);
        $resolver->setAllowedTypes('ojs_template_selection', ['null', 'string']);

        // Options Javascript pour les sources de données distantes
        $resolver->setDefaults([
            'ajax_route' => null,
            'ajax_param' => [],
            'ajax_scroll' => true,
            'ajax_delay' => 250,
            'ajax_cache' => true,
        ]);

        $resolver->setAllowedTypes('ajax_route', ['null', 'string']);
        $resolver->setAllowedTypes('ajax_param', ['array']);
        $resolver->setAllowedTypes('ajax_scroll', ['bool']);
        $resolver->setAllowedTypes('ajax_delay', ['int']);
        $resolver->setAllowedTypes('ajax_cache', ['bool']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // pass the form type option directly to the template
        $view->vars['color'] = $options['color'];

        // Options javascript du widget
        if (null === $options['ojs_template_result']) {
            unset($options['ojs_template_result']);
        }
        if (null === $options['ojs_template_selection']) {
            unset($options['ojs_template_selection']);
        }
        $view->vars['ojs_options'] = $this->getOptionsWidgetCamelized($options);

        // Options Javascript pour les sources de données distantes
        $view->vars['ajax_route'] = $options['ajax_route'];
        $view->vars['ajax_param'] = $options['ajax_param'];
        $view->vars['ajax_scroll'] = $options['ajax_scroll'];
        $view->vars['ajax_delay'] = $options['ajax_delay'];
        $view->vars['ajax_cache'] = $options['ajax_cache'];

        $view->vars['ajax_count'] = 20;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_select2';
    }
}

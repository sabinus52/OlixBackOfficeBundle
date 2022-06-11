<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Type;

use Doctrine\Persistence\ManagerRegistry;
use Olix\BackOfficeBundle\Form\DataTransformer\EntitiesToValuesTransformer;
use Olix\BackOfficeBundle\Form\DataTransformer\EntityToValueTransformer;
use Olix\BackOfficeBundle\Form\Model\AbstractModelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type select amélioré.
 *
 * @example     Configuration with options of this type
 * @example     @param bool   multiple              : True for multiple select and false for single select.
 * @example     @param string class                 : True will enable infinite scrolling
 * @example     @param string primary_key           : The name of the property used to uniquely identify entities
 * @example     @param string label_field           : The entity property used to retrieve the text for existing data
 * @example     @param Enum   color                 : Color of widget
 * @example     Config widget width JS parameters
 * @example     @param bool   allow_clear           : Causes a clear button ("x" icon) to appear on the select box when a value is selected
 * @example     @param bool   close_on_select       : Select2 will automatically close the dropdown when an element is selected
 * @example     @param string language              : Specify the language used for Select2 messages
 * @example     @param string placeholder           : Specifies the placeholder for the control.
 * @example     @param int    minimum_input_length  : Minimum number of characters required to start a search.
 * @example     Config for ajax remote datas
 * @example     @param string ajax_route            : Route of ajax remote datas
 * @example     @param array  ajax_param            : Parameters of route
 * @example     @param bool   ajax_scroll           : "infinite scrolling" for remote data sources out of the box
 * @example     @param int    ajax_delay            : The number of milliseconds to wait for the user to stop typing before issuing the ajax request
 * @example     @param bool   ajax_cache            :
 * @example     @see https://select2.org/configuration/options-api Liste des différentes options
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/select2/select2
 * @see         https://github.com/tetranz/select2entity-bundle (inspiration)
 */
class Select2AjaxType extends AbstractModelType
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'expanded' => false,
            'compound' => false,
            'data_class' => null,
            'multiple' => false,
            'color' => 'default',
            'class' => null,
            'primary_key' => 'id',
            'label_field' => null,
            'allow_clear' => true,
            'close_on_select' => true,
            'language' => 'fr',
            'minimum_input_length' => 0,
            'placeholder' => '',
            'ajax_route' => null,
            'ajax_param' => [],
            'ajax_scroll' => true,
            'ajax_delay' => 250,
            'ajax_cache' => true,
        ]);

        // Allowed types for options
        $resolver->setAllowedValues('expanded', [false]);
        $resolver->setAllowedValues('compound', [false]);
        $resolver->setAllowedTypes('color', ['string']);
        $resolver->setAllowedValues('color', self::COLORS);
        $resolver->setAllowedTypes('class', ['string']);
        $resolver->setAllowedTypes('primary_key', ['string']);
        $resolver->setAllowedTypes('label_field', ['string']);
        $resolver->setAllowedTypes('allow_clear', ['bool']);
        $resolver->setAllowedTypes('close_on_select', ['bool']);
        $resolver->setAllowedTypes('language', ['string']);
        $resolver->setAllowedTypes('minimum_input_length', ['int']);
        $resolver->setAllowedTypes('placeholder', ['string']);
        $resolver->setAllowedTypes('ajax_route', ['null', 'string']);
        $resolver->setAllowedTypes('ajax_param', ['array']);
        $resolver->setAllowedTypes('ajax_scroll', ['bool']);
        $resolver->setAllowedTypes('ajax_delay', ['int']);
        $resolver->setAllowedTypes('ajax_cache', ['bool']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $transformer = ($options['multiple'])
            ? new EntitiesToValuesTransformer($this->doctrine->getManager(), $options['class'], $options['primary_key'], $options['label_field'])
            : new EntityToValueTransformer($this->doctrine->getManager(), $options['class'], $options['primary_key'], $options['label_field']);
        $builder->addViewTransformer($transformer, true);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // pass the form type option directly to the template
        $view->vars['multiple'] = $options['multiple'];
        $view->vars['color'] = $options['color'];
        $view->vars['allow_clear'] = $options['allow_clear'];
        $view->vars['ojs_options'] = [
            'allowClear' => $options['allow_clear'],
            'closeOnSelect' => $options['close_on_select'],
            'language' => $options['language'],
            'minimumInputLength' => $options['minimum_input_length'],
            'placeholder' => $options['placeholder'],
        ];

        // Options Javascript pour les sources de données distantes
        $view->vars['ajax_route'] = $options['ajax_route'];
        $view->vars['ajax_param'] = $options['ajax_param'];
        $view->vars['ajax_scroll'] = $options['ajax_scroll'];
        $view->vars['ajax_delay'] = $options['ajax_delay'];
        $view->vars['ajax_cache'] = $options['ajax_cache'];

        // Pour les données multiples, le nom doit être un tableau
        if ($options['multiple']) {
            $view->vars['full_name'] .= '[]';
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_select2_ajax';
    }
}

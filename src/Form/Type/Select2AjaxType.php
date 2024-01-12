<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Olix\BackOfficeBundle\Form\DataTransformer\EntitiesToValuesTransformer;
use Olix\BackOfficeBundle\Form\DataTransformer\EntityToValueTransformer;
use Olix\BackOfficeBundle\Form\Model\Select2ModelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Widget de formulaire de type select amélioré.
 *
 * @example     Configuration with options of this type
 * @example     @param bool   multiple              : True for multiple select and false for single select.
 * @example     @param string class                 : The class of your entity
 * @example     @param string class_property        : The name of the property used to search the query
 * @example     @param string class_pkey            : The name of the property used to uniquely identify entities
 * @example     @param string class_label           : The name of the property used to retrieve the text for existing data
 * @example     @param int    page_limit            : Number items by page for the scroll
 * @example     Config for ajax remote datas
 * @example     @param string remote_route          : Route of ajax remote datas
 * @example     @param array  remote_params         : Parameters of route
 * @example     @param bool   ajax_js_scroll        : True will enable infinite scrolling
 * @example     @param int    ajax_js_delay         : The number of milliseconds to wait for the user to stop typing before issuing the ajax request
 * @example     @param bool   ajax_js_cache         :
 * @example     @see https://select2.org/configuration/options-api Liste des différentes options
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/select2/select2
 * @see         https://github.com/tetranz/select2entity-bundle (inspiration)
 */
class Select2AjaxType extends Select2ModelType
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        // Options symfony du widget
        $resolver->setDefaults([
            'compound' => false,
            'multiple' => false,
            'class' => null,
        ]);
        $resolver->setAllowedValues('compound', [false]);

        // Options supplémentaires pour l'entité
        $resolver->setDefaults([
            'class_property' => null,
            'class_pkey' => 'id',
            'class_label' => null,
            'page_limit' => 25,
        ]);
        $resolver->setAllowedTypes('class_property', ['string']);
        $resolver->setAllowedTypes('class_pkey', ['string']);
        $resolver->setAllowedTypes('class_label', ['string']);
        $resolver->setAllowedTypes('page_limit', ['int']);

        // Options supplémentaires pour l'appel url en Ajax
        $resolver->setDefaults([
            'remote_route' => null,
            'remote_params' => [],
        ]);
        $resolver->setAllowedTypes('remote_route', ['null', 'string']);
        $resolver->setAllowedTypes('remote_params', ['array']);

        // Options javascript pour la fonction Ajax
        $resolver->setDefaults([
            'ajax_js_scroll' => true,
            'ajax_js_delay' => 250,
            'ajax_js_cache' => true,
        ]);
        $resolver->setAllowedTypes('ajax_js_scroll', ['bool']);
        $resolver->setAllowedTypes('ajax_js_delay', ['int']);
        $resolver->setAllowedTypes('ajax_js_cache', ['bool']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $transformer = ($options['multiple'])
            ? new EntitiesToValuesTransformer($this->entityManager, $options['class'], $options['class_pkey'], $options['class_label'])
            : new EntityToValueTransformer($this->entityManager, $options['class'], $options['class_pkey'], $options['class_label']);
        $builder->addViewTransformer($transformer, true);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        // Options pour la création du widget
        $view->vars['allow_clear'] = $options['js_allow_clear'];

        // Génération de la route
        $options['ajax_js_route'] = $this->router->generate($options['remote_route'], array_merge($options['remote_params'], ['widget' => $form->getName()]));

        // Options Javascript pour les sources de données distantes
        $view->vars['attr'] += ['data-ajax' => json_encode($this->getOptionsWidgetCamelized($options, 'ajax_js_'))];

        // Pour les données multiples, le nom doit être un tableau
        if ($options['multiple']) {
            $view->vars['attr'] += ['multiple' => 'multiple'];
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

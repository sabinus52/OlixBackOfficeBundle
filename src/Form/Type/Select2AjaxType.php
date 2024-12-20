<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
    public function __construct(protected EntityManagerInterface $entityManager, protected RouterInterface $router)
    {
    }

    #[\Override]
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
            'allow_add' => false,
            'allow_add_prefix' => 'onew:',
            'callback' => null,
        ]);
        $resolver->setAllowedTypes('class_property', ['string']);
        $resolver->setAllowedTypes('class_pkey', ['string']);
        $resolver->setAllowedTypes('class_label', ['string']);
        $resolver->setAllowedTypes('page_limit', ['int']);
        $resolver->setAllowedTypes('allow_add', ['bool']);
        $resolver->setAllowedTypes('allow_add_prefix', ['string']);
        $resolver->setAllowedTypes('callback', ['null', 'callable']);

        // Options supplémentaires pour l'appel url en Ajax
        $resolver->setDefaults([
            'remote_route' => 'olix_autocomplete_select2', // Route par défaut
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
     * @param array<string,string> $options Options du widget
     */
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $transformer = ($options['multiple'])
            ? new EntitiesToValuesTransformer($this->entityManager, (string) $options['class'], (string) $options['class_pkey'], (string) $options['class_label'], (string) $options['allow_add_prefix'])
            : new EntityToValueTransformer($this->entityManager, (string) $options['class'], (string) $options['class_pkey'], (string) $options['class_label'], (string) $options['allow_add_prefix']);
        $builder->addViewTransformer($transformer, true);
    }

    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Autorisation d'ajout d'un nouvel élément
        if (true === $options['allow_add']) {
            // Pour autoriser Select2 à ajouter un élément
            $options['js_tags'] = true;
            // Pour déterminer que l'id est bien une nouvelle valeur <option value='onew:toto'>
            $view->vars['attr'] += ['data-prefix-new' => $options['allow_add_prefix']];
        }

        parent::buildView($view, $form, $options);

        // Options pour la création du widget
        $view->vars['allow_clear'] = $options['js_allow_clear'];

        // Génération de la route
        $options['ajax_js_route'] = $this->generateRoute($form, $options);

        // Options Javascript pour les sources de données distantes
        $view->vars['attr'] += ['data-ajax' => json_encode($this->getOptionsWidgetCamelized($options, 'ajax_js_'))];

        // Pour les données multiples, le nom doit être un tableau
        if ($options['multiple']) {
            $view->vars['attr'] += ['multiple' => 'multiple'];
            $view->vars['full_name'] .= '[]';
        }
    }

    /**
     * Génération de la route qui sera appelée par le widget Select2 pour l'autocomplétion.
     *
     * @param array<string,mixed> $options Options du widget
     */
    private function generateRoute(FormInterface $form, array $options): string
    {
        /** @var array<string, string> $optRemoteParams Paramètres de la route */
        $optRemoteParams = $options['remote_params'];

        // Formulaire parent
        $formParent = $form->getParent();
        if (!$formParent instanceof FormInterface) {
            throw new \RuntimeException(sprintf('Parent form of "%s" form is not a FormInterface', $form->getName()));
        }
        // Class du type de formulaire parent
        $classFormParent = $formParent->getConfig()->getType()->getInnerType()::class;

        return $this->router->generate((string) $options['remote_route'], // @phpstan-ignore cast.string
            array_merge($optRemoteParams, [
                'class' => $classFormParent,
                'widget' => $form->getName(),
            ])
        );
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_select2_ajax';
    }
}

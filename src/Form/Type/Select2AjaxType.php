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

        // Options javascript supplémentaires pour l'appel url en Ajax
        $resolver->setDefault('ajax', static function (OptionsResolver $ajaxResolver): void {
            $ajaxResolver->setDefaults([
                'url' => null,
                'route' => 'olix_autocomplete_select2', // Route par défaut
                'params' => [],
                'scroll' => true,
                'delay' => 250,
                'cache' => true,
            ]);
            $ajaxResolver->setAllowedTypes('route', ['null', 'string']);
            $ajaxResolver->setAllowedTypes('params', ['array']);
            $ajaxResolver->setAllowedTypes('scroll', ['bool']);
            $ajaxResolver->setAllowedTypes('delay', ['int']);
            $ajaxResolver->setAllowedTypes('cache', ['bool']);
        });
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
        if (true === (bool) $options['allow_add']) {
            // Pour autoriser Select2 à ajouter un élément
            $options[self::KEY_OPTS_JS]['tags'] = true;
            // Pour déterminer que l'id est bien une nouvelle valeur <option value='onew:toto'>
            $view->vars['attr'] += ['data-prefix-new' => $options['allow_add_prefix']];
        }

        // Options spécifique pour savoir s'il faut ajouter un élément vide dans la liste
        $view->vars['allow_clear'] = (array_key_exists('allow_clear', $options[self::KEY_OPTS_JS])) ? $options[self::KEY_OPTS_JS]['allow_clear'] : false;

        // Options Javascript pour les sources de données distantes en mode Ajax
        $options['ajax']['url'] = $this->generateRoute($form, $options['ajax']);
        $view->vars['attr'] += ['data-ajax' => json_encode($options['ajax'])];

        // Pour les données multiples, le nom doit être un tableau
        if ($options['multiple']) {
            $view->vars['attr'] += ['multiple' => 'multiple'];
            $view->vars['full_name'] .= '[]';
        }

        parent::buildView($view, $form, $options);
    }

    /**
     * Génération de la route qui sera appelée par le widget Select2 pour l'autocomplétion.
     * Récupère le formulaire parent et la classe du formulaire parent utilisé par le service AutoComplete.
     *
     * @param array<string,mixed> $optionsAjax Options des paramètres `ajax`
     */
    private function generateRoute(FormInterface $form, array &$optionsAjax): string
    {
        /** @var string $optAjaxRoute Route */
        $optAjaxRoute = $optionsAjax['route'];
        /** @var array<string, string> $optAjaxParams Paramètres de la route */
        $optAjaxParams = $optionsAjax['params'];

        // Récupère le formulaire parent
        $formParent = $form->getParent();
        if (!$formParent instanceof FormInterface) {
            throw new \RuntimeException(sprintf('Parent form of "%s" form is not a FormInterface', $form->getName()));
        }
        // Récupère la classe du type de formulaire parent
        $classFormParent = $formParent->getConfig()->getType()->getInnerType()::class;

        // Supprime ces 2 paramètres qui ne sont plus utiles une fois la route générée
        unset($optionsAjax['route'], $optionsAjax['params']);

        return $this->router->generate($optAjaxRoute,
            array_merge($optAjaxParams, [
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

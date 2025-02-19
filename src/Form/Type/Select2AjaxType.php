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
use Olix\BackOfficeBundle\Helper\Helper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Widget de formulaire de type select amélioré.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/select2/select2
 * @see         Liste des différentes options : https://select2.org/configuration/options-api
 * @see         https://github.com/tetranz/select2entity-bundle (inspiration)
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.StaticAccess)
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
            'class' => null,                // La classe de l'entité
        ]);
        $resolver->setAllowedValues('compound', [false]);

        // Options supplémentaires pour l'entité
        $resolver->setDefaults([
            'class_pkey' => 'id',           // Le nom de la propriété utilisée pour identifier chaque élément de l'entité
            'page_limit' => 25,             // Nombre d'éléments affichés par page pour le défilement
            'allow_add' => false,           // Option pour l'ajout d'un élément. `class_label` est requis
            'allow_add_prefix' => 'onew:',  // Préfixe de l'option "Ajouter" pour l'entité
            'callback' => null,             // Callback via le QueryBuilder pour la récupération des résultats
        ]);
        $resolver->setDefined([
            'class_property',               // Le nom de la propriété utilisée pour rechercher le query
            'class_label',                  // La nom de la propriété de l'entité utilisée pour récupérer le texte à afficher
        ]);
        $resolver->setAllowedTypes('class_property', ['string']);
        $resolver->setAllowedTypes('class_pkey', ['string']);
        $resolver->setAllowedTypes('class_label', ['string']);
        $resolver->setAllowedTypes('page_limit', ['int']);
        $resolver->setAllowedTypes('allow_add', ['bool']);
        $resolver->setAllowedTypes('allow_add_prefix', ['string']);
        $resolver->setAllowedTypes('callback', ['null', 'callable']);
        $resolver->setNormalizer('allow_add', static function (Options $options, bool $value) use ($resolver): bool {
            if ($value) {
                $resolver->setRequired('class_label');
            }

            return $value;
        });

        // Options javascript supplémentaires pour l'appel url en Ajax
        $resolver->setDefault('ajax', static function (OptionsResolver $ajaxResolver): void {
            $ajaxResolver->setDefaults([
                'url' => null,                          // Url générée avec `route`et `params` et utilisée par le service AutoComplete
                'route' => 'olix_autocomplete_select2', // Route par défaut
                'params' => [],                         // Paramètres de la route
                'scroll' => true,                       // True pour activer le défilement par page
                'delay' => 250,                         // Le nombre de millisecondes à attendre avant d'émettre la requête ajax
                'cache' => true,                        // True pour activer le cache
            ]);
            $ajaxResolver->setAllowedTypes('route', ['null', 'string']);
            $ajaxResolver->setAllowedTypes('params', ['array']);
            $ajaxResolver->setAllowedTypes('scroll', ['bool']);
            $ajaxResolver->setAllowedTypes('delay', ['int']);
            $ajaxResolver->setAllowedTypes('cache', ['bool']);
        });

        /**
         * @deprecated 1.2 : Options JavaScript pour la fonction Ajax
         */
        $resolver->setDefined([
            'remote_route',
            'remote_params',
            'ajax_js_scroll',
            'ajax_js_delay',
            'ajax_js_cache',
        ]);
        $resolver->setAllowedTypes('remote_route', ['null', 'string']);
        $resolver->setAllowedTypes('remote_params', ['null', 'array']);
        $resolver->setAllowedTypes('ajax_js_scroll', ['bool']);
        $resolver->setAllowedTypes('ajax_js_delay', ['int']);
        $resolver->setAllowedTypes('ajax_js_cache', ['bool']);

        $resolver->setDeprecated('remote_route', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "route" option of the "ajax" option instead.');
        $resolver->setDeprecated('remote_params', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "params" option of the "ajax" option instead.');
        $resolver->setDeprecated('ajax_js_scroll', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "scroll" option of the "ajax" option instead.');
        $resolver->setDeprecated('ajax_js_delay', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "delay" option of the "ajax" option instead.');
        $resolver->setDeprecated('ajax_js_cache', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "cache" option of the "ajax" option instead.');
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

    /**
     * @param array<string,array<string,mixed>> $options
     */
    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Autorisation d'ajout d'un nouvel élément
        if ((bool) $options['allow_add']) {
            // Pour autoriser Select2 à ajouter un élément
            $options['options_js']['tags'] = true;
            // Pour déterminer que l'id est bien une nouvelle valeur <option value='onew:toto'>
            $view->vars['attr'] += ['data-prefix-new' => $options['allow_add_prefix']];
        }

        // Options spécifique pour savoir s'il faut ajouter un élément vide dans la liste
        $view->vars['allow_clear'] = (array_key_exists('allow_clear', $options['options_js'])) ? $options['options_js']['allow_clear'] : false;

        // Options Javascript pour les sources de données distantes en mode Ajax
        $optionsAjaxDeprecated = Helper::getCamelizedKeys($options, 'ajax_js_'); /** @deprecated 1.2 */
        $options['ajax']['url'] = $this->generateRoute($form, $options['ajax']);
        $view->vars['attr'] += ['data-ajax' => json_encode($options['ajax'] + $optionsAjaxDeprecated)];

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

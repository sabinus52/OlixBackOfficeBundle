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
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/select2/select2
 * @see         Liste des différentes options : https://select2.org/configuration/options-api
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
            'class' => null,                // La classe de l'entité
        ]);
        $resolver->setAllowedValues('compound', [false]);

        // Options supplémentaires pour l'entité
        $resolver->setDefaults([
            'class_property' => null,       // Le nom de la propriété utilisée pour rechercher le query
            'class_pkey' => 'id',           // Le nom de la propriété utilisée pour identifier chaque élément de l'entité
            'class_label' => null,          // La nom de la propriété de l'entité utilisée pour récupérer le texte à afficher
            'page_limit' => 25,             // Nombre d'éléments affichés par page pour le défilement
            'allow_add' => false,           // Option pour l'ajout d'un élément. `class_label` est requis
            'allow_add_prefix' => 'onew:',  // Préfixe de l'option "Ajouter" pour l'entité
            'callback' => null,             // Callback via le QueryBuilder pour la récupération des résultats
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
        if (true === (bool) $options['allow_add']) {
            // Pour autoriser Select2 à ajouter un élément
            $options['options_js']['tags'] = true;
            // Pour déterminer que l'id est bien une nouvelle valeur <option value='onew:toto'>
            $view->vars['attr'] += ['data-prefix-new' => $options['allow_add_prefix']];
        }

        // Options spécifique pour savoir s'il faut ajouter un élément vide dans la liste
        $view->vars['allow_clear'] = (array_key_exists('allow_clear', $options['options_js'])) ? $options['options_js']['allow_clear'] : false;

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

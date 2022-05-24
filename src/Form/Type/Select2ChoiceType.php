<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Type;

use Olix\BackOfficeBundle\Form\Model\Select2ModelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type select amélioré depuis une liste de valeurs.
 *
 * @example     Configuration with options of this type
 * @example     @param Enum   color      : Couleur du widget
 * @example     @param string ajax_route : Name of route for remote data
 * @example     @param int    ajax_count : Max results returned in remote data
 * @example     Config widget with the parameter 'attr' : { params }
 * @example     @param string data-placeholder        : Specifies the placeholder for the control.
 * @example     @param int    data-minimumInputLength : Minimum number of characters required to start a search.
 * @example     @see https://select2.org/configuration/options-api Liste des différentes options
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         SwitchModelType::class
 */
class Select2ChoiceType extends Select2ModelType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'ajax_route' => null,
            'ajax_count' => 25,
        ]);

        $resolver->setAllowedTypes('ajax_route', ['null', 'string']);
        $resolver->setAllowedTypes('ajax_count', ['int']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        // pass the form type option directly to the template
        $view->vars['ajax_route'] = $options['ajax_route'];
        $view->vars['ajax_count'] = $options['ajax_count'];
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}

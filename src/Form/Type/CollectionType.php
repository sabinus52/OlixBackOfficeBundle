<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Type;

use Olix\BackOfficeBundle\Form\Model\AbstractModelType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType as SfCollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type "Colelction" amélioré.
 *
 * @example     Configuration with options of this type
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 */
class CollectionType extends AbstractModelType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'button_label_add' => 'Add',
        ]);
        $resolver->setAllowedTypes('button_label_add', ['string']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // pass the form type option directly to the template
        $view->vars['button_label_add'] = $options['button_label_add'];
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return SfCollectionType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_collection';
    }
}

<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form;

use Olix\BackOfficeBundle\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de mise à jour des infos de l'utilisateur.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class UserEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array<mixed>         $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class)
            ->add('name', TextType::class)
            ->add('enabled', CheckboxType::class, [])
            ->add('expiresAt')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',  // TODO prendre en compte les rôles
                    'Admin' => 'ROLE_ADMIN',
                    'Reader' => 'ROLE_READER',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php
/**
 * Formulaire de mise à jour des infos de l'utilisateur
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 * @package Olix
 * @subpackage BackOfficeBundle
 */

namespace Olix\BackOfficeBundle\Form;

use Olix\BackOfficeBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserEditType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class)
            ->add('name', TextType::class)
            ->add('enabled', CheckboxType::class, [        ])
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
     * @var OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
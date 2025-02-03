<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form;

use Olix\BackOfficeBundle\Form\Type\DatePickerType;
use Olix\BackOfficeBundle\Form\Type\SwitchType;
use Olix\BackOfficeBundle\Form\Type\TextType;
use Olix\BackOfficeBundle\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de mise à jour des infos de l'utilisateur.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class UserEditType extends AbstractType
{
    /**
     * @param array<mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'left_symbol' => '<i class="fas fa-user"></i>',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('enabled', SwitchType::class, [
                'label' => 'Compte actif',
                'on_color' => 'success',
                'off_color' => 'danger',
                'chk_label' => 'Compte activé ou pas',
                'required' => false,
            ])
            ->add('expiresAt', DatePickerType::class, [
                'label' => 'Compte expire le',
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER', /** Surcharger par une nouvelle classe formulaire pour y indiquer la liste des rôles */
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'switch-custom', // 'checkbox-custom',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

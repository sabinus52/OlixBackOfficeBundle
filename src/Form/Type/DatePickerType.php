<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Type;

use Olix\BackOfficeBundle\Form\Model\DateTimePickerModelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type DatePicker.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         DualListBoxModelType::class
 */
class DatePickerType extends DateTimePickerModelType
{
    private const COMPONENTS = [
        'calendar' => true,
        'date' => true,
        'month' => true,
        'year' => true,
        'decades' => true,
        'clock' => false,
        'hours' => false,
        'minutes' => false,
        'seconds' => false,
    ];

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'button_icon' => 'far fa-calendar-alt',
            'format' => 'dd/MM/yyyy',
        ]);
    }

    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (array_key_exists('display', $options[self::KEY_OPTS_JS])) {
            $options[self::KEY_OPTS_JS]['display'] += [
                'components' => self::COMPONENTS,
            ];
        } else {
            $options[self::KEY_OPTS_JS]['display'] = [
                'components' => self::COMPONENTS,
            ];
        }

        parent::buildView($view, $form, $options);
    }

    #[\Override]
    public function getParent(): string
    {
        return DateType::class;
    }
}

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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type DateTimePicker.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         DateTimePickerModelType::class
 */
class DateTimePickerType extends DateTimePickerModelType
{
    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'format' => 'yyyy-MM-dd hh:mm',
        ]);
    }

    #[\Override]
    public function getParent(): string
    {
        return DateTimeType::class;
    }
}

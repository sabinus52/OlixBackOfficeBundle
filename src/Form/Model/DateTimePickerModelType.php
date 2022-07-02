<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use DateTimeInterface;
use IntlDateFormatter;
use Locale;
use Olix\BackOfficeBundle\Helper\DateFormatConverter;
use RuntimeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type DateTime Picker.
 *
 * @example     Configuration with options of this type
 * @example     @param string button_icon : Icon from right input
 * @example     @param string locale
 * @example     Config widget JS parameter :
 * @example     @see https://getdatepicker.com/5-4/Options/ Liste des différentes options
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/tempusdominus/bootstrap-4
 * @see         https://getdatepicker.com/5-4/
 */
abstract class DateTimePickerModelType extends AbstractModelType
{
    /**
     * @var DateFormatConverter
     */
    private $dateFormatConverter;

    /**
     * @var string
     */
    private $locale = 'fr'; // TODO

    /**
     * Constructeur.
     */
    public function __construct()
    {
        $this->dateFormatConverter = new DateFormatConverter();
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'widget' => 'single_text',
            'html5' => false,
            'button_icon' => 'fas fa-calendar',
            'locale' => $this->locale,
        ]);

        $resolver->setAllowedValues('widget', ['single_text']);
        $resolver->setAllowedValues('html5', [false]);
        $resolver->setAllowedTypes('button_icon', ['string']);

        // Options JavaScript supplémentaires du widget
        $resolver->setDefaults([
            'ojs_stepping' => 5,
            'ojs_min_date' => false,
            'ojs_max_date' => false,
            'ojs_use_current' => false,
            'ojs_collapse' => true,
            'ojs_default_date' => false,
            'ojs_disabled_dates' => [],
            'ojs_enabled_dates' => [],
            'ojs_icons' => [
                'time' => 'far fa-clock',
                'date' => 'far fa-calendar-alt',
            ],
            'ojs_side_by_side' => false,
            'ojs_days_of_week_disabled' => [],
            'ojs_calendar_weeks' => false,
            'obj_view_mode' => 'days',
            'obj_keep_open' => false,
            'obj_disabled_time_intervals' => [],
            'obj_allow_input_toggle' => false,
            'obj_focus_on_show' => true,
            'obj_disabled_hours' => [],
        ]);

        $resolver->setAllowedTypes('ojs_stepping', 'int');
        $resolver->setAllowedTypes('ojs_min_date', ['bool', 'string', DateTimeInterface::class]);
        $resolver->setAllowedTypes('ojs_max_date', ['bool', 'string', DateTimeInterface::class]);
        $resolver->setAllowedTypes('ojs_use_current', 'bool');
        $resolver->setAllowedTypes('ojs_collapse', 'bool');
        $resolver->setAllowedTypes('ojs_default_date', ['bool', 'string', DateTimeInterface::class]);
        $resolver->setAllowedTypes('ojs_disabled_dates', 'array');
        $resolver->setAllowedTypes('ojs_enabled_dates', 'array');
        $resolver->setAllowedTypes('ojs_icons', 'array');
        $resolver->setAllowedTypes('ojs_side_by_side', 'bool');
        $resolver->setAllowedTypes('ojs_days_of_week_disabled', 'array');
        $resolver->setAllowedTypes('ojs_calendar_weeks', 'bool');
        $resolver->setAllowedTypes('obj_view_mode', 'string');
        $resolver->setAllowedTypes('obj_keep_open', 'bool');
        $resolver->setAllowedTypes('obj_disabled_time_intervals', 'array');
        $resolver->setAllowedTypes('obj_allow_input_toggle', 'bool');
        $resolver->setAllowedTypes('obj_focus_on_show', 'bool');
        $resolver->setAllowedTypes('obj_disabled_hours', 'array');
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $format = $options['format'];
        // pass the form type option directly to the template
        $view->vars['button_icon'] = $options['button_icon'];

        // Convert les options de types DateTime
        $options['ojs_min_date'] = $this->formatIsDate($options['ojs_min_date'], $format);
        $options['ojs_max_date'] = $this->formatIsDate($options['ojs_max_date'], $format);
        $options['ojs_default_date'] = $this->formatIsDate($options['ojs_default_date'], $format);
        foreach ($options['ojs_disabled_dates'] as $key => $value) {
            $options['ojs_disabled_dates'][$key] = $this->formatIsDate($value, $format);
        }
        foreach ($options['ojs_enabled_dates'] as $key => $value) {
            $options['ojs_enabled_dates'][$key] = $this->formatIsDate($value, $format);
        }

        // Options javascript du widget
        $view->vars['ojs_options'] = $this->getOptionsWidget($options);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'olix_datetimepicker';
    }

    /**
     * Retourne toutes les options javascript du widget datetimepicker.
     *
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    private function getOptionsWidget(array $options): array
    {
        // Camelize options for javascript
        $result = $this->getOptionsWidgetCamelized($options);

        $result['locale'] = $options['locale'];
        // Conversion format date PHP to format moment.js
        $result['format'] = $this->dateFormatConverter->convert($options['format']);

        return $result;
    }

    /**
     * @param string|DateTimeInterface $option
     * @param string                   $format
     *
     * @return string
     */
    private function formatIsDate($option, string $format)
    {
        if ($option instanceof DateTimeInterface) {
            $option = $this->formatObject($option, $format);
        }

        return $option;
    }

    /**
     * Formate une date de type DateTime dans le format spécifié du widget.
     *
     * @param DateTimeInterface $dateTime
     * @param string            $format
     *
     * @return string
     */
    private function formatObject(DateTimeInterface $dateTime, string $format): string
    {
        $formatter = new IntlDateFormatter($this->locale, IntlDateFormatter::NONE, IntlDateFormatter::NONE);
        $formatter->setPattern($format);

        $formatted = $formatter->format($dateTime);
        if (!is_string($formatted)) {
            throw new RuntimeException(sprintf('The format "%s" is invalid.', $format));
        }

        return $formatted;
    }
}

<?php

declare(strict_types=1);

/**
 *  This file is part of OlixBackOfficeBundle.
 *  (c) Sabinus52 <sabinus52@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Locale;
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
 * @example     @see https://getdatepicker.com/6/options/ Liste des différentes options
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/Eonasdan/tempus-dominus
 * @see         https://getdatepicker.com/
 */
abstract class DateTimePickerModelType extends AbstractModelType
{
    private string $locale = 'fr'; // TODO

    /**
     * Constructeur.
     */
    public function __construct()
    {
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
            'js_allow_input_toggle' => false,
            'js_default_date' => null,
            'js_use_current' => false,
            'js_stepping' => 5,
            'js_display' => [],
            'js_restrictions' => [],
        ]);

        $resolver->setAllowedTypes('js_allow_input_toggle', 'bool');
        $resolver->setAllowedTypes('js_default_date', ['null', 'string', \DateTimeInterface::class]);
        $resolver->setAllowedTypes('js_use_current', 'bool');
        $resolver->setAllowedTypes('js_stepping', 'int');
        $resolver->setAllowedTypes('js_display', 'array');
        $resolver->setAllowedTypes('js_restrictions', 'array');
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
        if (isset($options['js_restrictions']['minDate'])) {
            $options['js_restrictions']['minDate'] = $this->formatIsDate($options['js_restrictions']['minDate'], $format);
        }
        if (isset($options['js_restrictions']['maxDate'])) {
            $options['js_restrictions']['maxDate'] = $this->formatIsDate($options['js_restrictions']['maxDate'], $format);
        }
        $options['js_default_date'] = $this->formatIsDate($options['js_default_date'], $format);
        if (null === $options['js_default_date']) {
            unset($options['js_default_date']);
        }
        if (isset($options['js_restrictions']['disabledDates'])) {
            foreach ($options['js_restrictions']['disabledDates'] as $key => $value) {
                $options['js_restrictions']['disabledDates'][$key] = $this->formatIsDate($value, $format);
            }
        }
        if (isset($options['js_restrictions']['enabledDates'])) {
            foreach ($options['js_restrictions']['enabledDates'] as $key => $value) {
                $options['js_restrictions']['enabledDates'][$key] = $this->formatIsDate($value, $format);
            }
        }

        // Options javascript du widget
        $view->vars['js_options'] = $this->getOptionsWidget($options);
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

        $result['localization']['locale'] = $options['locale'];
        // Conversion format date PHP to format moment.js
        $result['localization']['format'] = $options['format'];

        if ([] === $result['restrictions']) {
            unset($result['restrictions']);
        }

        return $result;
    }

    /**
     * @param bool|string|\DateTimeInterface|null $option
     */
    private function formatIsDate($option, string $format): null|bool|string
    {
        if ($option instanceof \DateTimeInterface) {
            return $this->formatObject($option, $format);
        }

        return $option;
    }

    /**
     * Formate une date de type DateTime dans le format spécifié du widget.
     */
    private function formatObject(\DateTimeInterface $dateTime, string $format): string
    {
        $formatter = new \IntlDateFormatter($this->locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE);
        $formatter->setPattern($format);

        $formatted = $formatter->format($dateTime);
        if (!is_string($formatted)) {
            throw new \RuntimeException(sprintf('The format "%s" is invalid.', $format));
        }

        return $formatted;
    }
}

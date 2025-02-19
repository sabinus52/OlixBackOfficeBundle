<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Locale;
use Olix\BackOfficeBundle\Helper\Helper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type DateTime Picker.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://www.npmjs.com/package/@eonasdan/tempus-dominus
 * @see         Liste des différentes options : https://getdatepicker.com/6/options/
 *
 * @version     6.9.*
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
abstract class DateTimePickerModelType extends AbstractType
{
    private string $locale = 'fr';

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'widget' => 'single_text',
            'html5' => false,
            'button_icon' => 'fas fa-calendar',    // Icône du bouton du input
            'locale' => $this->locale,             // Langue du widget
            'options_js' => [],
        ]);

        $resolver->setAllowedValues('widget', ['single_text']);
        $resolver->setAllowedValues('html5', [false]);
        $resolver->setAllowedTypes('button_icon', ['string']);
        // Options supplémentaires JavaScript du widget
        $resolver->setAllowedTypes('options_js', ['array']);

        /**
         * @deprecated 1.2 : Options JavaScript du widget
         */
        $resolver->setDefined([
            'js_allow_input_toggle',
            'js_default_date',
            'js_use_current',
            'js_stepping',
            'js_display',
            'js_restrictions',
        ]);

        $resolver->setAllowedTypes('js_allow_input_toggle', 'bool');
        $resolver->setAllowedTypes('js_default_date', ['null', 'string', \DateTimeInterface::class]);
        $resolver->setAllowedTypes('js_use_current', 'bool');
        $resolver->setAllowedTypes('js_stepping', 'int');
        $resolver->setAllowedTypes('js_display', 'array');
        $resolver->setAllowedTypes('js_restrictions', 'array');

        $resolver->setDeprecated('js_allow_input_toggle', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "allowInputToggle" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_default_date', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "defaultDate" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_use_current', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "useCurrent" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_stepping', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "stepping" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_display', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "display" option of the "options_js" option instead.');
    }

    /**
     * @param array<string,array<string,mixed>> $options
     */
    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var string $format */
        $format = $options['format'];
        // Options javascript du widget
        /** @var array<string,array<string,mixed>> $optionsJavaScript */
        $optionsJavaScript = $options['options_js'];
        $optionsJavaScriptDeprecated = Helper::getCamelizedKeys($options, 'js_'); /** @deprecated 1.2 */

        // Ajoute les options javascript supplémentaires sur la locale et le format "moment.js"
        $optionsJavaScript['localization']['locale'] = $options['locale'];
        $optionsJavaScript['localization']['format'] = $options['format'];

        // Parcours les options JavaScript de types DateTime pour les convertir en format "moment.js"
        $this->convertAllOptionsIsDateInFormat($optionsJavaScript, $format);
        $this->convertAllOptionsIsDateInFormat($optionsJavaScriptDeprecated, $format);

        // Icône à droite cu widget et qui sert de bouton pour afficher le widget
        $view->vars['button_icon'] = $options['button_icon'];

        // Sélecteur du widget déjà définit dans le template : data-toggle='datetimepicker2'
        // Options javascript définit dans le template : data-options-js="{{ js_options }}"
        $view->vars['js_options'] = json_encode(array_merge_recursive($optionsJavaScriptDeprecated, $optionsJavaScript));
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_datetimepicker';
    }

    /**
     * Parcours et convertit les options JavaScript de types DateTime pour les convertir en format "moment.js".
     * Modifie directement les valeurs dans le tableau.
     *
     * @param array<string,mixed> $optionsJavaScript Options JavaScript du widget
     * @param string              $format            Format date du widget JS
     */
    private function convertAllOptionsIsDateInFormat(array &$optionsJavaScript, string $format): void
    {
        foreach ($optionsJavaScript as &$option) {
            if (is_array($option)) {
                // Si c'est un tableau, on continue son parcours récursivement
                $this->convertAllOptionsIsDateInFormat($option, $format);
            } elseif ($option instanceof \DateTimeInterface) {
                // Si c'est un DateTime, on le formate
                $option = $this->formatDateTime($option, $format);
            }
        }
    }

    /**
     * Formate une date de type DateTime dans le format "moment.js" spécifié du widget.
     */
    private function formatDateTime(\DateTimeInterface $dateTime, string $format): string
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

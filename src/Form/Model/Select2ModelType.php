<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Form\Model;

use Olix\BackOfficeBundle\Enum\ColorBS;
use Olix\BackOfficeBundle\Enum\ColorCSS;
use Olix\BackOfficeBundle\Helper\Helper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Widget de formulaire de type select amélioré.
 *
 * @author      Sabinus52 <sabinus52@gmail.com>
 *
 * @see         https://github.com/select2/select2
 * @see         Liste des différentes options : https://select2.org/configuration/options-api
 *
 * @version     4.1
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
abstract class Select2ModelType extends AbstractType
{
    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options du widget du formulaire
        $resolver->setDefaults([
            'expanded' => false,
            'color' => null,       // Couleur du widget
            'options_js' => [],
        ]);

        $resolver->setAllowedValues('expanded', [false]);
        $resolver->setAllowedTypes('color', ['null', 'string']);
        $resolver->setAllowedValues('color', [null] + array_merge(ColorCSS::values(), ColorBS::values()));
        // Options supplémentaires JavaScript du widget
        $resolver->setAllowedTypes('options_js', ['array']);

        /**
         * @deprecated 1.2 : Options JavaScript du widget
         */
        $resolver->setDefined([
            'js_allow_clear',
            'js_close_on_select',
            'js_language',
            'js_minimum_input_length',
            'js_placeholder',
            'js_width',
        ]);

        $resolver->setAllowedTypes('js_allow_clear', ['bool']);
        $resolver->setAllowedTypes('js_close_on_select', ['bool']);
        $resolver->setAllowedTypes('js_language', ['string']);
        $resolver->setAllowedTypes('js_minimum_input_length', ['int']);
        $resolver->setAllowedTypes('js_placeholder', ['string']);

        $resolver->setDeprecated('js_allow_clear', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "allowClear" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_close_on_select', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "closeOnSelect" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_language', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "language" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_minimum_input_length', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "minimumInputLength" option of the "options_js" option instead.');
        $resolver->setDeprecated('js_placeholder', 'olix/backoffice-bundle', '1.2', 'The "%name%" option is deprecated. Use the "placeholder" option of the "options_js" option instead.');

        $resolver->setNormalizer('color', static function (Options $options, ?string $value): ?string {
            $newValue = match ($value) {
                'primary' => ColorCSS::BLUE->value,
                'secondary' => ColorCSS::GRAY->value,
                'success' => ColorCSS::GREEN->value,
                'danger' => ColorCSS::RED->value,
                'warning' => ColorCSS::ORANGE->value,
                'info' => ColorCSS::CYAN->value,
                'light' => ColorCSS::GRAY->value,
                'dark' => ColorCSS::BLACK->value,
                default => $value,
            };

            if (in_array($value, ColorBS::values(), true)) {
                trigger_deprecation('olix/backoffice-bundle', '1.2',
                    'Dans le widget Select2, la couleur "%s" est obsolète, utilisez plutôt la couleur "%s" à la place.', $value, $newValue
                );
            }

            return $newValue;
        });
    }

    #[\Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Couleur du widget
        $view->vars['color'] = $options['color'] ?? 'default';

        // Sélecteur du widget
        $view->vars['attr'] += ['data-toggle' => 'select2'];

        // Options javascript du widget
        $optionsJavaScriptDeprecated = Helper::getCamelizedKeys($options, 'js_'); /** @deprecated 1.2 */
        $view->vars['attr'] += ['data-options-js' => json_encode($options['options_js'] + $optionsJavaScriptDeprecated)];
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'olix_select2';
    }
}

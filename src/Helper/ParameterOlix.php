<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Helper;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Classe d'aide aux paramètres de la configuration du bundle.
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class ParameterOlix
{
    /**
     * Configuration des options du thème.
     */
    private readonly ParameterBag $parameterBag;

    /**
     * @param array<mixed> $olixConfigParameter : Configuration du bundle 'olix_back_office'
     */
    public function __construct(array $olixConfigParameter)
    {
        $this->parameterBag = new ParameterBag($olixConfigParameter);
    }

    public function getValue(string $keySeparatorDot): bool|int|string
    {
        /** @var array<mixed> $options */
        $options = $this->parameterBag->all();

        // Récupère la valeur de la clé dans le tableau
        /** @var array<mixed>|bool|int|string|null $value */
        $value = Helper::getNestedValueFromArray($options, $keySeparatorDot, '[key-not-found]');
        if ('[key-not-found]' === $value) {
            throw new \Exception(sprintf('La clé "%s" des paramètres olix_back_office est inconnue', $keySeparatorDot));
        }

        if (is_array($value)) {
            throw new \Exception(sprintf('La clé "%s" des paramètres olix_back_office doit être une valeur', $keySeparatorDot));
        }
        // Si la valeur est null ou vide, retourne une chaîne vide au lieu de null
        if (null === $value) {
            return '';
        }

        return $value;
    }
}

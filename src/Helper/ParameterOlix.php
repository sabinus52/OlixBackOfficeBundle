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

    public function getValue(string $keySeparatorDot): mixed
    {
        /** @var array<mixed> $options */
        $options = $this->parameterBag->all();

        // Teste les clés des options dans le tableau
        $params = explode('.', $keySeparatorDot);
        foreach ($params as $key) {
            if (!array_key_exists($key, $options)) {
                throw new \Exception(sprintf('La clé "%s" des paramètres olix_back_office est inconnue', $keySeparatorDot));
            }

            // Passe au sous tableau
            /** @var array<mixed> $options */
            $options = $options[$key];
        }

        return $options;
    }
}

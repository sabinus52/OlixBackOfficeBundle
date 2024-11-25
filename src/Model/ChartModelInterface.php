<?php

declare(strict_types=1);

/**
 * This file is part of OlixBackOfficeBundle.
 * (c) Sabinus52 <sabinus52@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olix\BackOfficeBundle\Model;

/**
 * Interface de la classe d'un modèle de graphique.
 *
 * @author     Sabinus52 <sabinus52@gmail.com>
 */
interface ChartModelInterface
{
    public function getType(): string;

    /**
     * @return array<mixed>
     */
    public function getOptions(): array;

    /**
     * @param array<mixed> $datas
     */
    public function build(array $datas): void;
}
